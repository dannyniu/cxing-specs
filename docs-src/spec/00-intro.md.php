The <?= langname() ?> Programming Language
====

<small style="display: block; text-align: left;">Build Info:
  This build of the (draft) spec is based on git commit
  <?php system("git rev-parse HEAD") ?></small>

<small style="display: block; text-align: left;">
The 2025-12-26 revision of the draft spec is the 2nd feature-complete beta,
and is ready to be implemented for testing</small>

<?= hc_H1("Introduction") ?>


Goal
----

The '<?= langname() ?>' programming language (with or without caps) is a
general-purpose programming language with a C-like syntax that is
memory-safe, aims to be thread-safe, and have surprise-free semantics.
It aims to fit into and interoperate with the existing ecosystem written in
other languages, with C as its starting point.

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

The see section <?= hcNamedSection("Features") ?> for more information
on how the goals are achieved.

Naming
----

<?= namechoice()."\n\n" ?>

License
----

The language itself and the reference implementation are released into
the public domain.

<?= "\n\n" ?>
