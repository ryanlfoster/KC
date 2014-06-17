Use this module if you're intending to use SagePaySuite on Amazon hosting.

This fixes a couple of issues relating to getting the correct REMOTE_ADDR value
from the collection of addresses sent in REMOTE_ADDR and X_FORWARDED_FOR.

Place Rippleffect_SagePaySuite.xml in app/etc/modules to activate.

Note: Ebizmarts_SagePaySuite must be activated and installed before this module
can be activated.

-Dan