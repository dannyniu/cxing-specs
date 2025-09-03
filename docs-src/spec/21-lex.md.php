<div class="pagebreak"></div>

<?= hc_H1("Lexical Elements.") ?>

For the purpose of this section, the POSIX Extended Regular Expressions (ERE)
syntax is used to describe the production of lexical elements. The POSIX
regular expression is chosen for it being vendor neutral. There's a difference
between the POSIX semantic of regular expression and PCRE semantic, the latter
of which is widely used in many programming languages even on POSIX platforms,
most notably Perl, Python, PHP, and have been adopted by JavaScript. Care have
been taken to ensure the expressions used in this chapter are interpreted
identically under both semantics.

Comments in the code blocks begin with 2 forward slashes: `//`.

Identifiers and Keywords
----

An *identfier* has the following production: `[_[:alpha:]][_[:alnum:]]*`.
A *keyword* is an identifier that matches one of the following:

```
// Types:
long ulong double val ref

// Special Values:
true false null

// Phrases:
return break continue and or _Fallback

// Statements and Declarations:
decl

// Control Flows:
if else elif while do for

// Functions:
subr method ffi this

// Translation Unit Interface:
_Include extern
```

Numbers
----

*Decimal integer literals* have the following production: `[1-9][0-9]*[uU]?`.
When the literal has the "U" suffix, the literal has type `ulong`, otherwise,
the literal has type `long`.

*Octal integer literals* have the following production: `0[0-7]*`. An octal
literal always has type `ulong`.

*Hexadecimal integer literals* have
the following production: `0[xX][0-9a-fA-F]+`.
A hexadecimal literal always has type `ulong`.

*Fraction literals* has the following production: `[0-9]+\.[0-9]*|\.[0-9]+`.
The literal always has type `double`.

*Decimal scientific literals* is a fraction literal further suffixed by
a *decimal exponent literal* production: `[eE][-+]?[0-9]+`. The digits of the
production indicates a power of 10 to raise fraction part to.

*Hexadecimal fraction literal* has the following production:
`0[xX]([0-9a-fA-F]+.[0-9a-fA-F]*|.[0-9a-fA-F]+)` - this production is
*NOT a valid lexical element* in the language,
but *hexadecimal scientific literal* is, which is defined as
hex fraction literal followed by *hexadecimal exponent literal* - having the
production: `[pP][-+]?[0-9]+`. The digits of the production indicates a power
of 2 to raise the fraction part to.

Characters and Strings
----

*Character and string literals* have the following production:
`['"]([^\]|\\(["'abfnrtv]|x[0-9a-fA-F]{2,2}|[0-3][0-7]{0,2}))['"]`

In the 2nd alternative, each alternative have the following meanings:
1. Escaping
   - For single and double quote characters, they're represented literally
     and don't delimit the literal.
   - 'a' indicates the `BEL` ASCII 'bell' control character,
   - 'b' indicates the `BS` ASCII backspace character,
   - 'f' indicates the `FF` ASCII form-feed character,
   - 'n' indicates the `LF` ASCII line-feed character,
   - 'r' indicates the `CR` ASCII carriage return character,
   - 't' indicates the `HT` ASCII horizontal tab character,
   - 'v' indicates the `VT` ASCII vertical tab character.
2. Hexadecimal byte literal. The first character is interpreted as the high
   nibble of the byte, while the second the low.
3. Octal byte literal. The characters (total 3 at most) are interpreted as an
   octal integer literal used as value for the byte.

When single-quoted, the literal is a character literal having the value of the
first character as type `long`, the behavior is implementation-defined if there
are multiple characters.

When double-quoted, the literal is a string literal having type `str`.

Punctuations
----

A punctuation is one of the following:

```
( ) [ ] -> . ++ -- + - ~ ! * / %
<< >> >>> < > & ^ |
<= >= == != === !== && || ?? ? :
= *= /= %= += -= <<= >>= >>>= &= ^= |= &&= ||= ,
; { }
```
