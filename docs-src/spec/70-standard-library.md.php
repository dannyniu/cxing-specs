<div class="pagebreak"></div>

___Language Specification Afterwords___
====

**Omission of Features**

Many features of a modern programming environment such as I/O, filesystem,
signals, program execution, and even sockets are omitted from the standard
library of the language, eventhough some of them have pretty agreed-upon APIs.

Many of these features have platform-dependent variations. Although the POSIX
standard ironed out much of the kink, the merit of many of the design decisions
in POSIX are still debated, and we don't want to force programmers to "translate"
their code pattern into <?= langname() ?>. Instead, application-specific
platform logics is to be encapsulated when interfacing with <?= langname() ?>.

**External Extension**

The language has always been intended to be used in conjunction with C. We
do not agree with the C++ approach of "no language beneath".
While, we've not been confident enough to let <?= langname() ?> proclaim
the "zero-overhead principle" of:

> - you don't pay for what you don't use,
> - you don't do better than what we offer to do for you.

we do and _can_ proclaim:

> - every feature in the language has a reason to exist,
> - even if the reason is to let you extend the language
>   to implement features that doesn't exist yet.

**A Working Ecosystem**

We believe the true strength of the language is not that it be omnipotent, but
instead that it be **Minimal**, **Agile**, and **Maintainable**. Minimal, so that
when it's augmenting something, it doesn't carry too much of its own weight;
Agile, so that it can flex into every scenario by virtue of the existence of
an extension; Maintainable, every feature included has been considerately
if not rigorously designed, otherwise excluded, and the language and the
programs that use it shall last.

The spec not only defines the language, it also explain the usage of language
features. Further, it also explain how a feature may be implemented, as well
as implementation options. The usage and implementation recommendations
complement each other, so that the programmer can make their own best use
of language features, and implementations can find inspiration to improve on
the state of the art.

**An Interlude to the Library**

To tell the truth, as a test of how rigorous the features in the language is,
we pushed ourself to design a consistent threading library. We've went back
through a few iterations of the language and the library draft, and found a way
to make the language and the library to agree. The particular case with the
threading library is important, because it proves that the language features
are sufficient, even to serve the demand of the tricky semantic of threading.

<div class="pagebreak"></div>

<?= hc_H1("Standard Library") ?>

In the following sections, some special notations that're not part of langauge
are used for ease of presentation.

Firstly, the choice of type keywords in the description of standard library
is relaxed compared to the actual grammar rule of the language proper. The types
are explicitly indicated to be clear and explanative.

**Note**: The language proper had more restrictive rules because of the
potential overhead of executing/interpreting/evaluating the types, however once
implementation techniques are identified and strategy recommendations are
in place, the same relaxation may be made to language proper in a future time.

The other notation(s) used in the standard language are as follow:

**The meaning of such notation**:

```
<name1>(<name2>) := { ... }
```

*is as follow*:

The entity identified by `<name1>` is a subclass of `<name2>` (typically `val`),
and consist of additional members enumerated by the ellipsis `...`.
The word "subclass" is used here only to imply that object of type `<name1>`
may be used anywhere `<name2>` is expected. `<name2>` is not optional, because
it signifies to implementors of the runtime how an argument of such type
are to be passed.

**Note**: The notation is inspired by Python. Object-oriented programming is
not a supported paradigm of <?= langname() ?>. The notation is strictly for
presentation, and does not correspond to any existing language feature.

Because <?= langname() ?> is a dynamically typed language, typing is
not enforced, and the implementation does not diagnose typing errors (because
there aren't any). Checking the characteristics of an object is entirely
the responsibility of codes that use it.

Modules
----

The <?= langname() ?> is composed of modules. Language syntax and semantics are
specified in preceeding chapters, along with following chapters on mandatory
standard libraries, these form what's colliqually known as "Module-0".
Additional modules are optional, and should they exist, they specify interfaces
related to particular functionality. Certain interfaces of a particular module
may be specified in separate chapters if they're topically sparse.

For all library chapter in module-0, the following statement exists towards
the beginning of relevant chapters:

> This chapter forms an integral part of the language and
> its implementation is mandatory.

For library chapters pertaining to particular module, the following statement
exists towards the beginning of chapters making up the module:

> This chapter forms an integral part of module _X_ - should module _X_ be
> implemneted, this chapter along with any chapter constituting part of
> module _X_ must be implemented in their entirity.

Certain modules may have dependencies on others,
and the following statement may appear:

> This module depend on module _Y_, should this module be implemented,
> module _Y_ must also be implemented.
