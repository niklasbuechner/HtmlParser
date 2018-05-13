<?php
namespace HtmlParser\Tokenizer\Entities;

class EntitySearch
{
    /**
     * The shortest entity still has so many characters.
     */
    const AMOUNT_OF_CHARACTERS_IN_SHORTEST_ENTITY = 3;

    /**
     * A full list of html entities which can be downloaded at
     * https://html.spec.whatwg.org/entities.json.
     *
     * @var array
     */
    private static $entities = null;

    /**
     * Load all entities.
     *
     * @return array
     */
    private function getAllEntities()
    {
        if (!EntitySearch::$entities) {
            include __DIR__ . '/html-entities.php';
            EntitySearch::$entities = json_decode($entitiesJson, true);
        }

        return EntitySearch::$entities;
    }

    /**
     * Function to return the correct character for the named references in case the
     * character reference correctly started with '&' and ended with ';'.
     *
     * @param string $entityReference
     * @return string
     */
    public function getNamedCharacterEntity($entityReference)
    {
        $entities = $this->getAllEntities();
        $entityCode = ltrim(rtrim($entityReference, ';'), '&');

        if ($entities['&' . $entityCode . ';']) {
            $tmpStr = '';

            foreach ($entities['&' . $entityCode . ';']['codepoints'] as $codePoint) {
                $tmpStr .= html_entity_decode('&#' . $codePoint . ';');
            }

            return $tmpStr;
        } else {
            throw new \Exception('HTML entity was not found.');
        }
    }
}
