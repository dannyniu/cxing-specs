<div class="pagebreak"></div>

<?= hc_H1("Standard Library") ?>

In the following sections, some special notations that're not part of langauge
are used for ease of presentation.

**The meaning of such notation**:

```
[Function1 | Function2 | ... | FunctionN] := { ... }
```

*is as follow*:

An object whose members are listed in the brace may be created by and/or
returned from function(s) Function1 ... FunctionN.

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
