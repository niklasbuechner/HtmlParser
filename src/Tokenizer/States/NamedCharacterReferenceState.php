<?php
namespace HtmlParser\Tokenizer\States;

use \Exception;
use HtmlParser\Tokenizer\Entities\EntitySearch;
use HtmlParser\Tokenizer\Tokenizer;

class NamedCharacterReferenceState extends AbstractCharacterReferenceState implements State
{
    /**
     * @inheritdoc
     */
    public function processCharacter($character, Tokenizer $tokenizer)
    {
        switch ($character) {
            case ';':
                $tokenizer->appendToTemporaryBuffer($character);

                $this->processHtmlEntity($tokenizer, $tokenizer->getTemporaryBuffer());
                break;

            default:
                $tokenizer->appendToTemporaryBuffer($character);

                if (!preg_match('/[a-zA-Z0-9]/', $character)) {
                    $this->processHtmlEntity($tokenizer, $tokenizer->getTemporaryBuffer());
                }
                break;
        }
    }

    /**
     * Process an html entity from the temporary buffer.
     *
     * @param Tokenizer $tokenizer
     * @param string $htmlEntity
     * @param string $trailingCharacters
     */
    private function processHtmlEntity(Tokenizer $tokenizer, $htmlEntity, $trailingCharacters = '')
    {
        try {
            $this->createCharacterFromReferenceAndFlush($tokenizer, $htmlEntity, $trailingCharacters);
        } catch (Exception $exception) {
            $this->retryCharacterCreationRecursively($tokenizer, $htmlEntity, $trailingCharacters);
        }
    }

    /**
     * Creates the character from the html entity and flushes it correctly.
     *
     * @param Tokenizer $tokenizer
     * @param string $htmlEntity
     * @param string $trailingCharacters
     */
    private function createCharacterFromReferenceAndFlush(Tokenizer $tokenizer, $htmlEntity, $trailingCharacters = '')
    {
        $entitySearch = new EntitySearch();
        $entity = $entitySearch->getNamedCharacterEntity($htmlEntity);

        if ($this->preventAutoCorrectionForAttributeValues($tokenizer, $trailingCharacters)) {
            // For hisotrical reasons, incorrect character references in attribute value
            // states do not get autocorrection applied.
            $this->flushCodePoints();
        }

        $tokenizer->clearTemporaryBuffer();
        $this->flushCharacterFromEntity($tokenizer, $entity);

        $tokenizer->setState($tokenizer->getReturnState());
        for ($i = 0; $i < mb_strlen($trailingCharacters); $i++) {
            $tokenizer->getState()->processCharacter($trailingCharacters[$i], $tokenizer);
        }
    }

    /**
     * For historic reasons, no auto correction is applied to character references not ending
     * in ';' and being trailed by an alphanumeric character or an equals sign.
     *
     * @param Tokenizer $tokenizer
     * @param string $trailingCharacters
     */
    private function preventAutoCorrectionForAttributeValues(Tokenizer $tokenizer, $trailingCharacters)
    {
        if ($trailingCharacters === '') {
            return false;
        }

        $isAttributeValueCharacer = $tokenizer->getReturnState() instanceof AbstractAttributeNameState;
        $trailedByEqualsSign = mb_substr($trailingCharacters, 0, 1) === '=';
        $trailedByAlphaNumericCharacter = preg_match('/[a-zA-Z0-9]/', mb_substr($trailingCharacters, 0, 1));

        return $isAttributeValueCharacer && ($trailedByEqualsSign || $trailedByAlphaNumericCharacter);
    }

    /**
     * Tries to create a character recursively by removing one character and retrying the creation process.
     * @param Tokenizer $tokenizer
     * @param string $htmlEntity
     * @param string $trailingCharacters
     */
    private function retryCharacterCreationRecursively(Tokenizer $tokenizer, $htmlEntity, $trailingCharacters = '')
    {
        if (mb_strlen($htmlEntity, '8bit') >= EntitySearch::AMOUNT_OF_CHARACTERS_IN_SHORTEST_ENTITY + 1) {
            return $this->processHtmlEntity(
                $tokenizer,
                mb_substr($htmlEntity, 0, mb_strlen($htmlEntity, '8bit') - 1, '8bit'),
                mb_substr($htmlEntity, mb_strlen($htmlEntity, '8bit') - 1, 1, '8bit') . $trailingCharacters
            );
        } else {
            $tokenizer->clearTemporaryBuffer();
            $tokenizer->appendToTemporaryBuffer($htmlEntity);
            $this->flushCodePoints($tokenizer);

            $tokenizer->setState(new AmbiguousAmbersandState());
        }
    }
}
