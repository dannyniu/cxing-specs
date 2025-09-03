The <?= langname() ?> Programming Language
====

<small style="display: block; text-align: left;">Build Info:
  This build of the (draft) spec is based on git commit
  <?php system("git rev-parse HEAD") ?></small>

<?= hc_H1("Introduction") ?>


Goal
----

The '<?= langname() ?>' programming language (with or without caps) is a
general-purpose programming language with a C-like syntax that is
memory-safe, aims to be thread-safe, and have suprise-free semantics.
It aims to have foreign interface with other programming languages,
with C as its primary focus.

It attempts to pioneer in the field of efficient, expressive, and robust
error handling using language design toolsets.

The language is meant to be an open standard with multiple independent
implementations that are widely interoperable. It can be implemented
either as interpreted or as compiled. Programs written in <?= langname()." " ?>
should be no less portable than when it's written in C.

Features are introduced on strictly maintainable basis. The reference
implementation will be an AST-based interpreter (or a transpiler to C?),
which will serve as instrument of verification for additional implementations.
The version of the language (if it ever changes) will be independent of the
versions of the implementations.

The [Features](#features) section has more information
on how the goals are achieved.

Naming
----

<?= namechoice()."\n\n" ?>

License
----

The language itself and the reference implementation are released into
the public domain.

<?= "\n\n" ?>
