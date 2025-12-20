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

Comments
----

Comments in the language begin with 2 forward slashses: `//`, or 1 hash
sign: `#`, and span towards the end of the line. Another form of comments
exists, where it begins with `/*` and ends with `*/` - this form of comment
can span multiple lines.

Comments in the following explanatory code blocks use the same notation as in
the actual language.

Identifiers and Keywords
----

An *identfier* has the following production: `[_[:alpha:]][_[:alnum:]]*`.
A *keyword* is an identifier that matches one of the following:

```
// Types:
void long ulong double val obj in out

// Special Values:
true false null

// Phrases:
return break continue and or _Fallback

// Statements and Declarations:
decl

// Control Flows:
if else elif while do for

// Functions:
subr method this

// Translation Unit Interface:
_Include extern
```

Numbers
----

*Decimal integer literals* have the following production: `[1-9][0-9]*[uU]?`.
When the literal has the "U" suffix, the literal has type `ulong`, otherwise,
the literal has type `long`.

*Octal integer literals* have the following production: `0o?[0-7]*`. An octal
literal always has type `ulong`.

**Note**: As it had been a common mistake in newcomers to zero-pad a decimal
number only to realize it's become an octal literal, it is recommended that
implementations issue warnings when a number is zero-padded and recommend user
to prefix the literal with `0o` when they do intend to use octals. Likewise,
for some functions (e.g. `chmod` in POSIX), users may actually _DO_ intend to
use octals when they forget to zero-prefix them to become octal literals - in
these cases, it is recommended that semantic analysis be performed using syntax
information (if possible) and appropriate warnings be given.

*Hexadecimal integer literals* have
the following production: `0[xX][0-9a-fA-F]+`.
A hexadecimal literal always has type `ulong`.

*Radix-64 literals* have the following production: `0\\[A-Za-z0-9._]+`.
The primary use of radix-64 literals are as option flags to functions, as
bitwise compositions are obscure, and symbolic constants need verbose prefixes
to not pollute global name space. A radix-64 literal always have type `ulong`.
The characters following the backslash have the same numerical value as those
in the [Base 64 Encoding with URL and Filename Safe Alphabet](https://www.rfc-editor.org/rfc/rfc4648#section-5)
except that the minus sign (`-`) is replaced with a period (`.`) due to possible
ambiguity with the subtraction expression operator, and that there's no
padding characters.

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
`['"]([^\]|\\(["'abefnrtv]|x[0-9a-fA-F]{2,2}|[0-7]{1,3}))['"]`

In the 2nd subexpression, each alternative have the following meanings:
1. Escaping
   - For single and double quote characters, they're represented literally
     and don't delimit the literal.
   - 'a' indicates the `BEL` ASCII 'bell' control character,
   - 'b' indicates the `BS` ASCII backspace character,
   - 'e' indicates the `ESC` ASCII escape character,
   - 'f' indicates the `FF` ASCII form-feed character,
   - 'n' indicates the `LF` ASCII line-feed character,
   - 'r' indicates the `CR` ASCII carriage return character,
   - 't' indicates the `HT` ASCII horizontal tab character,
   - 'v' indicates the `VT` ASCII vertical tab character.
2. Hexadecimal byte literal. The first character is interpreted as the high
   nibble of the byte, while the second the low.
3. Octal byte literal. The characters (total 3 at most) are interpreted as an
   octal integer literal used as value for the byte. If there are 3 digits,
   then the first digit must be between 0 and 3.

When single-quoted, the literal is a character literal having the value of the
first character as type `long`, the behavior is implementation-defined if there
are multiple characters.

When double-quoted, the literal is a string literal having type `str`.

*Raw string literals* have the following production:
`\\("[^"]*"|'[^']')`

In a raw string literal, there is no escape sequence. Single quotes cannot
appear in single-quoted raw string literals, and double quotes cannot appear
in double-quoted raw string literals.

Raw string literals are primarily intended for writing regular expressions.

Any number of raw string and double-quoted string may be concatenated into one
string object by virtue of them being placed in adjacency with no character
in between other than whitespaces. The set of whitespace characters are defined
to be exactly the following: U+0020 (space), U+000D (carriage return),
U+000B (vertical tab), U+000A (line-feed), U+0009 (horizontal tab).

Punctuations
----

A punctuation is one of the following:

```
( ) [ ] =? . ++ -- + - ~ ! * / %
<< >> >>> < > & ^ |
<= >= == != === !== && || ?? ? :
= *= /= %= += -= <<= >>= >>>= &= ^= |= ,
; { }
```
