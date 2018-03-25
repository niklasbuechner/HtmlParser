<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\State;

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
        $stringLength = count($htmlString);

        for ($i = 0; $i < $stringLength; $i++) {
            $currentCharacter = $htmlString[$i];
            $this->state->processCharacter($currentCharacter, $this);
        }

        return [];
    }
}