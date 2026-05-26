<?= hc_H1("Standard Object Types") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

<?= hc_H2("Dynamic Data Structure Types") ?>

<?= hc_H3("Dictionary / Hash Table") ?>

```
[subr dict()] := {
  method __get__(k),
  method __set__(k, v),
  method __copy__(),
  method __final__(),
  method __unset__(k),
  method firstkey(),
  method nextkey(k),
}
```

The function `dict` creates a dictionary, also known as associative arraies, or
hash table (from the implementation's perspective) in literatures.
The semantics of `__get__`, `__set__`, `__copy__`, `__final__`, and `__unset__`
are as described in <?= hcNamedSection("Object/Value Key Access") ?>.

Before returning, `dict` shall initialize the dictionary with a method member
with the name of `__initset__` which is used for object definition notation.
When this method is called with `__proto__` as the first argument (discounting
the `this` argument since it's a method), it shall remove itself from the dictionary.

**Note**: <?= langname() ?> conflates 2 usages for the dict type -
first, as a data structure type holding unordered set of keyed values;
second, to support the '_appearence_' of so-called object-oriented programming.
The second usage was set as part of the expressiveness goal for the language
since its inception, the behavior of `__initset__` is largely irrelevant
for this usage. For the first usage however, it is undesirable that
the `__keys__` method enumerates `__initset__`, as one of the results.
Hence the new 2026-03-12 requirement for the default implementation
of `__initset__` to unset itself.

There is an undefined order of all keys in the dictionary which is relevant for
discussion the cardinarlity of the set of keys in a dictionary. The `firstkey`
and the `nextkey` methods can be used to iterate over the keys in a dictionary:
- When `firstkey` method is called, it returns the key that is ordered before
  all other keys.
- When the `nextkey` called with a key that is present in the dictionary, it
  returns the key that is ordered adjacently next to the called key from the
  argument. In particular, if the called key is ordered after all keys present
  in the dictionary, `null` is returned. The keys corresponding to
  type-associated properties are not returned.

**Note**: The `firstkey` and the `nextkey` method replaces the earlier snapshot
technique for enumerating dictionary keys, as the latter suffers from high
memory usage with large dictionaries.

<?= hc_H3("Ordered Set / Array") ?>

```
[subr array()] := {
  method __get__(k),
  method __set__(k, v),
  method __copy__(),
  method __final__(),
  method __initset__(k,v),
  method len(),
  method trunc(newlength),
  method swap(a,b),
  method move2head(index),
  method move2tail(index),
}
```

The function `array` creates an array, which is an ordered set. The semantics
of `__get__`, `__set__`, `__copy__`, and `__final__`, are as described
in <?= hcNamedSection("Object/Value Key Access") ?>, except with the
restriction that the argument `k` must be a runtime implementation defined
mapping from an integer.

The semantics of the method `__initset__` is as described
in <?= hcNamedSection("Type Definition and Object Initialization Syntax") ?>,
with similar restriction on `k`.

The `len` method returns the length of the array. The `trunc` function resizes
the array to `newlength`, and returns the array if successful, or `null` on failure.

The `swap` method swaps the value at the index position `a` and `b` within
the array. This method is intended for implementations of sorting algorithms.
The `move2head` and `move2tail` methods removes the element at `index` position
in the array, shifting all elements preceeding (for `move2head`) or following
(for `move2tail`) it, and place the element at the head or the tail of the list.
These 3 functions returns the array on success.
The implementation of these 3 method ought to avoid extroneous copying.

<?= hc_H2("Type Reflection") ?>

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
A non-null value uncasts to 0 - this makes sense because most POSIX API calls
return 0 on success and -1 on error, and `errno` failure codes uncasted from
blessed `null`s are non-zero.
