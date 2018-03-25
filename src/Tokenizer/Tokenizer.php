<?php
namespace HtmlParser\Tokenizer;

use HtmlParser\Tokenizer\States\State;

interface Tokenizer
{
    /**
     * Main function to tokenize HTML strings.
     *
     * @param string $htmlString
     * @return array
     */
    public function tokenize($htmlString);

    /**
     * Sets the state the current tokenization process is in.
     * A list of all states can be found at https://html.spec.whatwg.org/multipage/parsing.html#tokenization.
     */
    public function setState(State $state);

    /**
     * Emits the current token to the listener of the Tokenizers.
     */
    public function emitCurrentToken();
}
