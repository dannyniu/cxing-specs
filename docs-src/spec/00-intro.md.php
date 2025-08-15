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
implementation will be an AST-based interpreter (or a transpiler to C?),
which will serve as instrument of verification for additional implementations.
The version of the language (if it ever changes) will be independent of the
versions of the implementations.

The [Features](#features) section has more information
on how the goals are achieved.

**Naming**

<?= $namechoice."\n\n" ?>

License
----

The language itself and the reference implementation are released into
the public domain.

<style id="cxing-typesetting-commons">
 body {
   font-family:   Times New Roman, serif;
   font-size:     11pt;
   text-align:    justify;
   orphans:       10;
   widows:        14;
 }

 @page {
   size:          A4;
   margin:        18mm 20mm;
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

<style>
 div.booktitle {
   text-align:   center;
   margin:       3pc 2pc;
   font-size:    185%;
   font-weight:  bold;
 }

 div.abstract {
   margin:       0% 15%;
 }

 div.toc-list-head {
   font-weight:  bold;
   font-family:  serif;
   font-size:    125%;
 }

 ol.toc-list {
   list-style:   none;
 }

 ol.toc-list a {
   display:      block;
 }

 ol.toc-list a[href*="#h1-"] {
   margin-top:   1ex;
   font-weight:  bold;
 }

 *[data-a-prefix]::before {
   content:      attr(data-a-prefix) " ";
 }

 @media print
 {
   ol.toc-list a {
     width:              87%;
     position:           relative;
     /* border-bottom:      dotted; */
     text-decoration:    none;
   }

   ol.toc-list a::after {
     /* There is currently no ``leader()'' support in WeasyPrint. */
     position:   absolute;
     right:      0;
     content:    target-counter(attr(href url), page);
   }

   div.pagebreak {
     break-after:        always;
   }
 }

 nav.navbar-multipage {
   margin:       3mm 0mm;
   padding:      3mm 2mm;
   border:       solid 1pt gray;
   box-shadow:   1mm 1mm 2mm rgba(0,0,0,0.2);
 }
</style>

<style id="hc-configurable">
 @media print
 {
   @page:right
   {
     @bottom-right {
       content:  counter(page);
     }
   }

   @page:left
   {
     @bottom-left {
       content:  counter(page);
     }
   }

   @page {
     @top-center {
       content:  string(booktitle);
     }

     @bottom-center {
       content:  string(ch1);
     }
   }

   .booktitle {
     string-set: booktitle content();
   }

   h1 {
     string-set: ch1 content();
   }

   /* -- section: elements' fonts -- */

   :root {
     font-family:
       TeX Gyre Termes, FreeSerif,
       Times New Roman, serif;
   }

   h1, h2, h3, h4, h5, h6 {
     font-family:
       TeX Gyre Heros, FreeSans,
       Helvetica, Arial, sans-serif;
   }

   pre, code, kbd, samp {
     font-family:
       TeX Gyre Cursor, FreeMono,
       Courier New, monospace;
   }

   /* -- begin: paragraph numbering -- */

   body > p, body > pre,
   main > p, main > pre {
     counter-increment:  paragraph-cnt;
     position:           relative;
   }

   body > p::before, body > pre::before,
   main > p::before, main > pre::before {
     content:      counter(paragraph-cnt);
     font-family:  Times New Roman, serif;
     font-size:    8pt;
     text-align:   right;
     display:      block;
     position:     absolute;
     left:         -12mm;
     width:        10mm;
     height:       100%;
   }
 }
</style>
