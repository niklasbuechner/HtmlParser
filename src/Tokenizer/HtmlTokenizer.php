<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\State;
use HtmlParser\Tokenizer\Tokenizer;

class HtmlTokenizer extends AbstractTokenizer
{
    /**
     * Executes the tokenization process on a html string
     *
     * @param string $htmlString
     * @return array
     */
    public function tokenize($htmlString)
    {
        $this->setState(new DataState());
        $characters = preg_split('//u', $htmlString, -1, PREG_SPLIT_NO_EMPTY);
        $stringLength = count($characters);

        for ($i = 0; $i < $stringLength; $i++) {
            $currentCharacter = $characters[$i];
            $this->state->processCharacter($currentCharacter, $this);
        }

        $this->state->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $this);

        return [];
    }
}
