<?= hc_H1("Library for Multi-Threading") ?>

<?= hc_H2("Exclusive and Sharable Objects and Mutices (Mutex)") ?>

```
sharableObj(val) := { /* Sharable objects may be used across threads */ }
mutex_inst(sharableObj) := { /* Mutices are a class of sharable objects */ }

[ffi] subr [mutex_inst] mutex(val v);

mutex_inst(val) := {
  [ffi] method [exclusiveObj] acquire(),
}

exclusiveObj(val) := {
  // Exclusive objects can only be used by 1 thread at a time,
  // but is more efficient than shared objects when used.
  [ffi] method [val] __copy__(),
  [ffi] method [null] __final__(),
}
```

The `mutex()` function creates a mutex which is a sharable object that can be
used across threads. The argument `v` will be an exclusive object protected by
the mutex.

The `acquire()` method of a mutex returns a value native object
representing `v` - when the function returns, it is guaranteed that the thread
in which it returns is the only thread holding the value protected by the mutex,
and that until the value goes out of scope, no other thread may simultaneously
use the value.

The `__copy__()` and `__final__()` properties increments and decrements
respectively, a conceptual counter - this counter is initially set to 1 by
`acquire()` and any future functions that may be defined fulfilling similar
role; when it reaches 0, the mutex is 'unlocked', allowing other threads to
acquire the value for use.

**Note**: A typical implementation of `acquire()` may lock a mutex, sets the
conceptual counter to 1, creates and returns a value native object. A typical
implementation of the `__copy__()` method may be as simple as just incrementing
the conceptual counter. A typical implementation of the `__final__()` method
may decrement the counter, and when it reaches 0, unlocks the mutex.

**Note**: The conceptual counter is distinct from the reference count of any
potential resources used by the value protected by the mutex and the mutex itself.

-- TODO --: Thread management need to cater to the type system of <?= langname() ?>,
C/POSIX API have thread entry points take a pointer, but <?= langname() ?>
don't expose pointers. This along with other issues are to be addressed before
the threading library is formalized. The part with mutex is roughly okay now.
