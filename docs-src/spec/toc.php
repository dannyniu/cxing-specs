<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));
 $draft = true;

 foreach( glob("*.inc.php") as $inc )
   include $inc;

 $Title = ($draft ? "Draft " : ""). "Specification for the $langname Programming Language";
 $Cover = "rfc-draft";

 // Good < Ok < DONE.

 // Intro //
 hcAddPages("00-intro"); # Ok(2025-07-31).
 hcAddPages("01-features"); # Ok(2025-07-31).

 // Language //
 hcAddPages("21-lex");
 hcAddPages("22-expressions");
 hcAddPages("23-statements"); # Good(2025-07-31).
 hcAddPages("24-functions");
 hcAddPages("25-translation-unit-interface");
 hcAddPages("31-langsem"); # Pending Confirmation.
 hcAddPages("32-objdef"); # Good(2025-07-31).
 hcAddPages("33-numerics");
 hcAddPages("34-rtsem");

 // Standard Library //
 hcAddPages("70-standard-library");
 hcAddPages("71.01-string");
 hcAddPages("71.02-struct");
 hcAddPages("71.03-types");

 # Outstanding tasks:
 # 1. full object run-time interoperability across implementations, 2025-07-30: made a page on ABI, WIP.
 # 2. declaration syntax, 2025-07-30: DONE.
 # 3. conditional and iterated statements. DONE
 # 4. statement labels. DONE
 # 5. finalizer.
 # 6. standard library.

 hcFinish();
