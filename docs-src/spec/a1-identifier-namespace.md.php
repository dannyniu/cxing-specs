<?= hc_H1("Identifier Namespace") ?>

The goal of this section is to avoid ambiguity of identifiers in the global
namespace - i.e. avoiding the same identifier with conflicting meanings.

To this end, "commonly-used" refers to the attribute of an entity where it's
used so frequently that having a verbose spelling would hamper the
readability of the code.

When an identifier consist of multiplie words, the following terms are defined:
- Pascal Case: where each word, including the first, are capitalized,
- Camel Case: where each word except the first are capitalized,
- Snake Case: underscore-concatentated lowercase words,
- Verbose Case: underscore-concatenated Pascal case.

<?= hc_H2("Reserved Identifiers") ?>

Identifiers in the global namespace that begins with an underscore, followed
by an uppercase letter is reserved for standardization by the language.

Identifiers which consist of less than 10 lowercase letters or digits are
potentially reserved for standardization by the language, as keywords or
as "commonly-used" library functions or objects. Although the use of the word
"potentially" signifies that the reservation is not uncompromising, 3rd-party
library vendors should nontheless refrain from defining such terse identifiers
in the global namespace.

<?= hc_H2("Conventions for Identifiers") ?>

- For type objects, Pascal or Verbose case is recommended.
- For static functions and constants, Snake or Verbose case is recommended.
- For members and methods, Camel or Pascal case is recommended.
