<?= hc_H1("Dynamic Data Structure Types") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

```
subr dict_inst dict();

dict_inst(obj) := {
  method val __get__(val k),
  method val __set__(val k, val v),
  method val __copy__(),
  method void __final__(),
  method val __unset__(val k),
  method val __initset__(val k, val v),
  method dict_key_snapshot __keys__(),
}

dict_key_snapshot(obj) := {
  method val __get__(val k),
  method val __copy__(),
  method void __final__(),
}
```

The function `dict` creates a dictionary, also known as associative arraies, or
hash table (from the implementation's perspective) in literatures. It returns
a `dict_inst` - a dictionary instance. The semantics of `__get__`, `__set__`,
`__copy__`, `__final__`, and `__unset__` are as described in
<?= hcNamedSection("Object/Value Key Access") ?>,
The member `__initset__` SHALL NOT be a type-associated property.

The `__keys__()` method retrieves an immutable snapshot of the keys present
on the dictionary, at the time of the snapshot, and returns an object
consisting of the type-associated method  properties `__get__()`, `__copy__()`,
and `__final__()`.

The `__get__()` method may be used to retrieve the `count` of keys in the
snapshot, as well as the keys themselves indexed 0 through `count-1`. The
order of the keys are unspecified.

<?= hc_H1("Type Reflection") ?>

```
subr bool isnull(val x);
subr bool islong(val x);
subr bool isulong(val x);
subr bool isdouble(val x);
subr long _Uncast(val x);
subr obj objcls(val x);
```

The functions `isnull`, `islong`, `isulong`, `isdouble`, determines whether
the value is the special value `null`, of type `long`, type `ulong`, or
type `double` respectively.

The function `_Uncast` performs uncasting of `null`s - an operation whose
semantic is described in <?= hcNamedSection("Types and Special Values") ?>.

The function `objcls` classifies the value and determines the prototype of the
object. If the value is an object, it returns the `__proto__` property of the
object as if by `__get__` function, otherwise, it returns a blessed `null` that
uncasts to 0.
