<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));
 $draft = true;

 foreach( glob("*.inc.php") as $inc )
 {
   include $inc;
 }

 $Title = ($draft ? "Draft " : ""). "Specification for the ".langname()." Programming Language";
 $Cover = "rfc-draft";

 // Good < Ok < DONE.

 // Intro //
 hcAddPages("00-intro"); # Ok(2025-07-31).
 hcAddPages("01-features"); # Ok(2025-07-31).

 // Language //
 hcAddPages("21-lex");
 hcAddPages("22-expressions"); # Good(2025-08-25).
 hcAddPages("23-statements"); # Good(2025-07-31), Ok(2025-08-25).
 hcAddPages("24-functions");
 hcAddPages("25-translation-unit-interface");
 hcAddPages("31-langsem"); # Pending.
 hcAddPages("32-objdef"); # Good(2025-07-31), Revised(2025-10-02), Still WIP.
 hcAddPages("33-numerics"); # Good(2025-08-26).
 hcAddPages("34-rtsem");

 // Standard Library //
 hcAddPages("70-standard-library");
 hcAddPages("71.01-string");
 hcAddPages("71.02-struct");
 hcAddPages("71.03-types");
 hcAddPages("72.01-stdfp");
 hcAddPages("72.02-regex");
 hcAddPages("72.03-stdthr");

 hc_StartAnnexes();
 hcAddPages("a1-identifier-namespace");

 # Outstanding tasks:
 # 6. standard library - Done with a rationale of being incomplete.
 # 7 (2025-08-26). multi-threading and synchronization - DONE 2025-11-14.
 # 8. idea for a "propagate" operator - the opposite of "fallback".

 hcFinish();
