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

        parent::tokenize($htmlString);

        $this->state->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $this);
    }
}
