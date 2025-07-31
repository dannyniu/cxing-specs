Functions
====

```
function-declaration % funcdecl
: "subr" identifier arguments-list statement % subr
| "method" identifier arguments-list statement % method
| "traitdef" identifier arguments-list statement % traitdef

| "ffi" "subr" postfix-expr identifier arguments-list statement % ffisubr
| "ffi" "method" postfix-expr identifier arguments-list statement % ffimethod
| "ffi" "traitdef" postfix-expr identifier arguments-list statement % ffitraitdef
;

arguments-list % arglist
: "(" ")" % empty
| arguments-begin ")" % some
;

arguments-begin % args
: "(" postfix-expr identifier % base
| arguments-begin "," postfix-expr genrule
```

The `postfix-expr` MUST NOT be `inc` or `dec`. Furthermore, if `postfix-expr`
is `degenerate`, then the primary expression MUST NOT be `array` or `const`.

- For `subr`, `method`, and `traitdef`,
  - the `postfix-expr` in `arguments-list` MUST be the identifier `val` or the
    identifier `ref`. This is because arguments in non-FFI functions need to
    carry type information. When it's `val` the corresponding argument is
    passed by value; when it's `ref`, the corresponding argument is passed by
    reference.

- For `ffisubr`, `ffimethod`, and `ffitraitdef`,
  - the `postfix-expr` in `arguments-list` MUST NOT be the identifier `val`.
    This is because arguments in FFI functions are types native to the machine
    interface (i.e. native to the C ABI).
  - the `postfix-expr` before `identifer` determines the return type of the
    function.

- For all six forms of `function-declaration`, `statement` MUST be either:
  - `emptystmt`, in which case the `function-declaration` declares a function,
  - `brace`, in which case the `function-declaration` defines a function.
