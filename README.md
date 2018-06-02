# HTMLParser
A HTML parser implemented in PHP.

Assumptions:
- The input string is unicode.
- The input is a "normal" html page not an iframe srcdoc document.

Further information about parsing:
- The html is parsed as if scripting was enabled.
- The parser always outputs a complete html tree, even when parsing only a fragment.
- The parser might not handle svg correctly.
