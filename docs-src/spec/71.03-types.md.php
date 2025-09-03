<?= hc_H1("Type Reflection") ?>

```
[ffi] subr [bool] isnull(val x);
[ffi] subr [bool] islong(val x);
[ffi] subr [bool] isulong(val x);
[ffi] subr [bool] isdouble(val x);
[ffi] subr [bool] isobj(val x, val proto);
```

The functions `isnull`, `islong`, `isulong`, `isdouble`, determines whether
the value is the special value `null`, of type `long`, type `ulong`, or
type `double` respectively. The function `isobj` determines whether the value
is an object, if `proto` is not `null`, then it further determines whether
the `__proto__` member of the object is equal to `proto`.
