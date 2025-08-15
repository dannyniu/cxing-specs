<?= hc_H1("Functions") ?>

```
function-declaration % funcdecl
: "subr" identifier arguments-list statement % subr
| "method" identifier arguments-list statement % method

| "ffi" "subr" postfix-expr identifier arguments-list statement % ffisubr
| "ffi" "method" postfix-expr identifier arguments-list statement % ffimethod
;

arguments-list % arglist
: "(" ")" % empty
| arguments-begin ")" % some
;

arguments-begin % args
: "(" postfix-expr identifier % base
| arguments-begin "," postfix-expr identifier % genrule
```

The `postfix-expr` MUST NOT be `inc` or `dec`. Furthermore, if `postfix-expr`
is `degenerate`, then the primary expression MUST NOT be `array` or `const`.

- For `subr`, and `method`,
  - the `postfix-expr` in `arguments-list` MUST be the identifier `val` or the
    identifier `ref`. This is because arguments in non-FFI functions need to
    carry type information. When it's `val` the corresponding argument is
    passed by value; when it's `ref`, the corresponding argument is passed by
    reference.

- For `ffisubr`, and `ffimethod`,
  - the `postfix-expr` in `arguments-list` MUST NOT be the identifier `val`.
    This is because arguments in FFI functions are types native to the machine
    interface (i.e. native to the C ABI).
  - the `postfix-expr` before `identifer` determines the return type of the
    function.

- For all four forms of `function-declaration`, `statement` MUST be either:
  - `emptystmt`, in which case the `function-declaration` declares a function,
  - `brace`, in which case the `function-declaration` defines a function.

The type and order of parameters between all declarations and the definition of
the function MUST be consistent, furthermore, whether a function is a method or
a subroutine, is or is not an FFI function MUST be consistent. The name of the
parameters may be changed in the source code of a program. Depending on the
context, this may provide the benefit of both explanative argument naming in
declaration, and avoidance identifier collision in function definition when
the argument is appropriately renamed.
