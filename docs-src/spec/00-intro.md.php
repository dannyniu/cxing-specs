<div class="pagebreak"></div>

<?= "The '$langname' Programming Language\n" ?>
====

<small>Build Info:
This build of the (draft) spec is based on git commit
<?php system("git rev-parse HEAD") ?></small>

Introduction
----

**Goal**

The '<?= $langname ?>' programming language (with or without caps) is a
general-purpose programming language with a C-like syntax that is
memory-safe, aims to be thread-safe, and have suprise-free semantics.
It aims to have foreign interface with other programming languages,
with C as its primary focus.

It attempts to pioneer in the field of efficient, expressive, and robust
error handling using language design toolsets.

The language is meant to be an open standard with multiple independent
implementations that are widely interoperable. It can be implemented
either as interpreted or as compiled. Programs written in <?= "$langname " ?>
should be no less portable than when it's written in C.

Features are introduced on strictly maintainable basis. The reference
implementation will be an AST-based interpreter, which will serve as
instrument of verification for additional implementations. The version of
the language (if it ever changes) will be independent of the versions of
the implementations.

The [Features](#features) section has more information
on how the goals are achieved.

**Naming**

<?= $namechoice."\n\n" ?>

License
----

The language itself and the reference implementation are released into
the public domain.

<style>
  body {
    font-family:   Times New Roman, serif;
    font-size:     11pt;
    text-align:    justify;
  }

  @page {
    size:          A4;
    margin:        12mm 18mm;
  }

  .pagebreak {
    display:       block;
    break-before:  always;
  }

  h1 {
  }

  main {
    orphans:       10;
    widows:        14;
  }

  h1, h2, h3, h4, h5, h6 {
    text-align:    left;
    font-family:   Arial, Verdana, sans-serif;
  }

  code, pre {
    text-align:    left;
    font-family:   Courier New, Courier, monospace;
    background:    #F0F0F0;
    border-radius: 2pt;
  }

  code {
    font-size:     91%;
  }

  code {
    display:   inline-block;
    padding:   1pt 3pt;
    margin:    1pt;
  }

  li {
    margin:    3mm 1pt;
  }

  var {
    font-family:   serif;
  }
</style>
