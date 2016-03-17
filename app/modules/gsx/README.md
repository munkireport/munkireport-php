GSX module
==============

Updates warranty from Apple, designed as a warranty module replacement 

The GSX table provides the following information:

- Warranty status
- Coverage end date
- Coverage start date
- Days of coverage remaining, as last pulled from GSX
- Estimated purchase date
- Country of purchase
- Registration date
- Product decryption
- Shipping configuration
- Contract coverage end date
- Contract coverage start date
- Warranty contract type
- Labor covered
- Parts covered
- Warranty Service Level Agreement
- Is Mac a GSX loaner Mac
- Human readable warranty status
- Is vintage Mac
- Is obsolete Mac

The GSX module also updates the warranty and machine tables with the following:

- Purchase date
- Warranty end date
- Warranty status

- Product description


-- 

Data can be viewed under the GSX tab on the client details page or using the GSX listings view

Remarks
---

* The client triggers the server to do a lookup once a day
* Obsolete Macs are not available from Apple and are instead processed using the warranty module's backend

