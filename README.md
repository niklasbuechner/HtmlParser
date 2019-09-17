# HTMLParser
A HTML parser implemented in PHP.

## Learn goals
When I created this project, the intention was to learn (and I did learn) about the HTML specification and how to
implement a lexer and parser. Furthermore, this is my first project in which I used Test Driven Development.

While the project was never finished due to its size, most of the expected features are there. Not only is there
a full HTML lexer but also most of the HTML parser. There are however some issues with the adoption agency algorithm
since it never got fully implemented due to other time constraints. This ultimately lead to this project not being
finished.

You can still run all the tests and thereby use the parser by running `composer test`. (After installing all
dependencies with `composer install`.)

## Additional information for myself during the development
Assumptions:
- The input string is unicode.
- The input is a "normal" html page not an iframe srcdoc document.

Further information about parsing:
- The html is parsed as if scripting was enabled.
- The parser always outputs a complete html tree, even when parsing only a fragment.
- The parser might not handle svg correctly.
