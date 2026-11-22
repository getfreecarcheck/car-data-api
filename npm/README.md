# GetFreeCarCheck: UK car data API for JavaScript

Car reliability, common MOT faults, mileage norms, safety recalls and CO2 for over 550 UK car models, from over
100 million DVSA MOT tests. Model data is free with no key. MOT history by number plate (every test, defects,
mileage record with rollback warnings, ULEZ, car tax and recalls) with a [free key](https://getfreecarcheck.co.uk/developers#key):
500 lookups a month, no card.

```sh
npm install getfreecarcheck
```

```js
import { GetFreeCarCheck } from "getfreecarcheck";

const gfc = new GetFreeCarCheck();
const { data, meta } = await gfc.reliability("Ford", "Fiesta");
console.log(`${data.name}: ${data.failRate}% first-time MOT fails, ${data.vsAverage}% vs the average car its age`);
console.log(meta.attribution); // credit line to show with the data

const keyed = new GetFreeCarCheck({ key: process.env.GFC_KEY });
const car = await keyed.check("AB12 CDE"); // vehicle, MOT history, mileage, ULEZ, tax, recalls
```

Every answer carries `meta`: the sources, licence, a dated citation and the matching page on getfreecarcheck.co.uk.
Free plans show "Data: GetFreeCarCheck" with a link wherever the data appears.

- Docs and every field: https://getfreecarcheck.co.uk/developers/docs
- OpenAPI: https://api.getfreecarcheck.co.uk/v1/openapi.json
- AI assistants (MCP): https://api.getfreecarcheck.co.uk/mcp
- Plans: https://getfreecarcheck.co.uk/developers#plans

Contains public sector information licensed under the Open Government Licence v3.0, from the DVSA and the VCA.
