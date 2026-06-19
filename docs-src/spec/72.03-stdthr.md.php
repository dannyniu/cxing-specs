<div class="pagebreak"></div>

<?= hc_H1("Library for Multi-Threading") ?>

> This chapter forms an integral part of "The Multi-Threading Module" - should
> this module be implemneted, this chapter along with any chapter constituting
> part of "The Multi-Threading Module" must be implemented in their entirity.

<?= hc_H2("Exclusive and Sharable Objects and Mutices (Mutex)") ?>

```
// - Sharable objects may be used across threads
// - Exclusive objects have more efficient implementations than
//   sharable objects, but the behavior is undefined when used
//   in multiple threads.

[subr mutex(v)] := {
  method __copy__(),
  method __final__(),
  [method acquire()] := {
    method __get__(),
    method __set__(),
    method __copy__(),
    method __final__(),
  },
}
```

The `mutex()` function creates a mutex which is a sharable object that can be
used across threads. The argument `v` will be an exclusive object protected by
the mutex.

The destruction of resources is governed by the `__copy__` and `__final__` methods.
Each copy of the mutex produced by the `__copy__` method shall refers to the same
underlying mutex.

**Note**: If implemented using reference count, the `__copy__` and the `__final__`
methods should use atomic reference counts, or protect such count using a
mechanism distinct from that for `v`, in order to avoid deadlock during the
lifetime of the gift.

The `acquire()` method of a mutex returns a "gift" object that can be used
for accessing `v` - when the function returns, it is guaranteed that the thread
in which it returns is the only thread holding the value protected by the mutex,
and that until the gift object goes out of scope, there should be no other
thread simultaneously using the value.

**Note**: The "gift" object is so named, that the exclusive gift is wrapped
under a mutex, protected by it before being revealled to the acquiring thread.

The `__get__()` and the `__set__()` methods are used to access the object
protected by the mutex. When they're called with the string `v` as its key
argument, they respectively returns and sets the object protected by the mutex;
on all other values, they returns `null`. Note that the object loses the
protection of the mutex if it does not go out of scope when the gift
object does.

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

<?php /*
Since this is a 64-bit langauge, it is assumed that whatever platform that
provides greater than 32-bit memory space will need multiple cores to handle
large amount of data, and therefore will necessarily provide synchronization
primitives - especially atomic operations (even if emulated), that enabled
efficient implementation of mutecies.
*/ ?>

<?= hc_H2("Condition Variables") ?>

```
[subr condvar(mtx)]  := {
  method __copy__(),
  method __final__(),
  method wait(),
  method broadcast(),
  method signal(),
}
```

The `condvar()` function creates a condition variable. It monitors a condition
associated with the states protected by the mutex identified by `mtx`.

The destruction of resources is governed by the `__copy__` and `__final__` methods.
Each copy of the condition variable produced by the `__copy__` method shall
refers to the same underlying condition variable.

**Note**: If implemented using reference count, the `__copy__` and the `__final__`
methods should use atomic reference counts, or protect such count using a
mechanism distinct from that of the associated mutex, in order to avoid deadlock
during the lifetime of the gift.

The `wait()` method of a condition variable instance does the following:

1. unlocks the mutex `mtx` specified in the creation argument,
2. blocks the calling thread, and
3. wake up when and if the condition variable is signalled,
4. returns with mutex acquired -

all in one single atomic step.

The `broadcast()` method of a condition variable signals a condition variable
and wakes up all threads that're waiting on it. The `signal()` method signals
the condition varialbe and wakes up an unspecified subset of threads blocked
on the condition variable - this subset shall not be empty if there are threads
waiting on the condition variable, and this method should typically be more
efficient than `broadcast()` when there's only 1 waiting thread.

<?= hc_H2("Thread Management") ?>

```
[subr thrd_create(thrd_entry, thrd_param) | subr thrd_self()] := {
  method __copy__();
  method __final__();
  method join();
  method detach();
  method equals(thrd_hnd t2);
}
subr thrd_exit();
```

The resource management functions `__copy__` and `__final__` pertains to
that of the handle itself, and does not affect execution of any thread.

The `thrd_create()` function creates a thread with the `thrd_entry` as its
entry point, and `thrd_param` as its first and only argument. `thrd_entry`
MUST be a subroutine. Its return type is `null`. On success, a `thrd_hnd`
thread handle is returned, otherwise, `null` is returned.

The `thrd_self()` function returns the thread handle corresponding to the
current thread.

The `thrd_exit()` function cause the current thread to immediately terminate，
without cleaning up any resource allocated on the stack, or on the heap on
behalf of the thread.

The `join()` method of a `thrd_hnd` blocks the calling thread until the thread
referred to by the thread handle termintates. The first such call on a
non-detached thread is supposed to succeed - implementation shall document the
underlying platform API behavior for it; subsequent calls may not necessarily
succeed. The `detach()` method of a thread handle detaches a thread, after
which, the thread may no longer be joinable, or be detached again. The return
values of these 2 functions are implementation-defined.

The `equals()` method returns `true` if the thread handle `t2` refers to the
same thread as the thread handle on which the method is called, and `false`
otherwise.

The `thrd_hnd` shall be sharable across threads. The existence of a thread
handle does not imply that of the thread.

**Note**: The thread management facility is bare minimum, so that first it's
directly implementable using existing standard APIs. That second the thread
handle type `thrd_hnd` carries the least complexity, enabling its share across
threads - although it's not explicitly specified as a sharable type, it shall
behave as such. That third, the usage flexibility makes higher level
constructions such as asynchronously completing subroutines, coroutines,
single-apartment proxy objects, etc. be readily implementable in terms of
the minimal API.
