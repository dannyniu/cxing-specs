<?= hc_H1("Translation Unit Interface") ?>

A translation unit consist of a series of function declarations and
definitions. Because definition of objects occur during run time, it's not
possible to define data objects of static storage duration in <?= langname() ?>,
this is recognized as unfortunate and accepted as a design decision.

A translation unit in <?= langname() ?> correspond to relocatable code object,
or a file contain such information. We choose such definition to emphasize
binary runtime portability; the word "translate/translation" doesn't require
translation to occur - it's allowed for an implementation to interpret the
source code and execute it directly for when it can be achieved. The terms
"translation unit" and "relocatable object" take their usual commonly accepted
meanings in building programs and applications.


<?= hc_H2("Translation Unit Source Code Syntax") ?>

The goal symbol of a source code text string is `TU` - the translation unit
production. It consist of a series of entity declarations.

```
TU % TU
: entity-declaration % base
| TU entity-declaration % genrule
;

entity-declaration % entdecl
: "_Include" string-literal ";" % srcinc
| "extern" function-declaration % extern
| function-declaration % implicit
;
```

There MUST NOT be more than 1 *definition* of a function.

By default, all entity declarations are internal to the translation unit. For a
declaration to be visible in multiple translation units, it must be declared
"external" with  the `extern` keyword.

As a best practice, external declarations should be kept in "header" files, and
included (explained shortly) in a source code file. The recommended filename
extension for <?= langname() ?> source code file is
<?php assert( langname() === "cxing" ); ?>
`.cxing`, and `.hxing` for headers
(named after the Hongxing Yu <!-- 红星圩 --> village on the Changxing Island).

<?= hc_H2("Source Code Inclusion") ?>

Source code inclusion is a limited form of reference to external definitions.
This is *not* preprocessing, *not* importation, and *not* substitute for
linking. Source code inclusion is exclusively for sharing the declarations in
multiple source code files and translation units.

By default, header files are first searched in a set of pre-defined paths.
(These paths are typically hierarchy organized and implemented using a file
system.) If the header isn't found in the pre-defined paths, then it's searched
relative to the path of the source code file. However, if the string literal
naming the header file begins with `./` or `../`, then it's first searched
relative to the path of the source code file, then the pre-defined set of paths.
