<?= hc_H1("Dynamic Data Structure Types") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

```
[subr dict()] := {
  method __get__(k),
  method __set__(k, v),
  method __copy__(),
  method __final__(),
  method __unset__(k),
  method __initset__(k, v),
  [method __keys__()] := {
    method __get__(k),
    method __copy__(),
    method __final__(),
  },
}
```

The function `dict` creates a dictionary, also known as associative arraies, or
hash table (from the implementation's perspective) in literatures.
The semantics of `__get__`, `__set__`, `__copy__`, `__final__`, and `__unset__`
are as described in <?= hcNamedSection("Object/Value Key Access") ?>,
The member `__initset__` SHALL NOT be a type-associated property.

The `__keys__()` method retrieves an immutable snapshot of the keys present
on the dictionary, at the time of the snapshot, and returns an object
consisting of the type-associated method  properties `__get__()`, `__copy__()`,
and `__final__()`.

The `__get__()` method may be used to retrieve `length` which indicates the
number of keys in the snapshot, as well as the keys themselves indexed 0
through `length-1`. The order of the keys are unspecified.

<?= hc_H1("Type Reflection") ?>

```
subr isnull(x);
subr islong(x);
subr isulong(x);
subr isdouble(x);
subr _Uncast(x);
```

The functions `isnull`, `islong`, `isulong`, `isdouble`, determines whether
the value is the special value `null`, of type `long`, type `ulong`, or
type `double` respectively.

The function `_Uncast` performs uncasting of `null`s - an operation whose
semantic is described in <?= hcNamedSection("Types and Special Values") ?>.

**TODO 2025-12-26**: decide what to do with non-null arguments for uncasting.
