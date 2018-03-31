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
     *
     * @param State $state
     */
    public function setState(State $state);

    /**
     * Some states can be entered from multiple other states. The return state
     * defines which state they should return to. E.g. CharacterReferenceState
     *
     * @param State $state
     */
    public function setReturnState(State $state);

    /**
     * States might save data temporarily if the correct tokenisation path is
     * unknown and multiple paths have to be tested.
     *
     * @param string $character
     */
    public function appendToTemporaryBuffer($character);

    /**
     * Read the temporary buffer once the correct tokenisation path has been
     * discovered.
     *
     * @return string
     */
    public function getTemporaryBuffer();

    /**
     * Emits the current token to the listener of the Tokenizers.
     */
    public function emitCurrentToken();
}
