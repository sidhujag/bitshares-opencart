bitshares-opencart
======================

# Installation

Copy the contents of the upload folder into your OpenCart directory.

# Configuration


1. In the opencart administration under Extensions->Payments, click the "Install"
   link on the Bitshares row.
2. Also under Extensions->Payments, click the "Edit" link on the Bitshares row.
3. Configure the extension settings including RPC settings
4. Set the status to enabled (this activates the bitshares payment extension and 
    enabled shoppers to select the bitshares payment method).


# Usage

When a shopping chooses the Bitshares payment method, they will be presented with an
order summary as the next step (prices are shown in whatever currency they've selected
for shopping).  They will be presented with a button called "Pay with Bitshares."  This
button takes the shopper to a Bitshares invoice by opening the Bitshares wallet.  Once payment is received, a link is presented to the 
shopper that will take them back to the order summary.


## OpenCart Support

* [Homepage](http://www.opencart.com/)
* [Documentation](http://docs.opencart.com/)
* [Forums](http://forum.opencart.com/)

# Contribute

To contribute to this project, please fork and submit a pull request.

# License

The MIT License (MIT)

Copyright (c) 2011-2014 Bitshares

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
