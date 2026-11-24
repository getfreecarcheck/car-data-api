# GetFreeCarCheck: UK car data API for Python

Car reliability, common MOT faults, mileage norms, safety recalls and CO2 for over 550 UK car models, from over
100 million DVSA MOT tests. Model data is free with no key. MOT history by number plate (every test, defects,
mileage record with rollback warnings, ULEZ, car tax and recalls) with a [free key](https://getfreecarcheck.co.uk/developers#key):
500 lookups a month, no card.

```sh
pip install getfreecarcheck
```

```python
from getfreecarcheck import GetFreeCarCheck

gfc = GetFreeCarCheck()
r = gfc.reliability("Ford", "Fiesta")
print(f"{r['data']['name']}: {r['data']['failRate']}% first-time MOT fails")
print(r["meta"]["attribution"])  # credit line to show with the data

keyed = GetFreeCarCheck(key="gfc_live_...")
car = keyed.check("AB12 CDE")  # vehicle, MOT history, mileage, ULEZ, tax, recalls
```

Every answer carries `meta`: the sources, licence, a dated citation and the matching page on getfreecarcheck.co.uk.
Free plans show "Data: GetFreeCarCheck" with a link wherever the data appears.

- Docs and every field: https://getfreecarcheck.co.uk/developers/docs
- OpenAPI: https://api.getfreecarcheck.co.uk/v1/openapi.json
- AI assistants (MCP): https://api.getfreecarcheck.co.uk/mcp
- Plans: https://getfreecarcheck.co.uk/developers#plans

## Browser extension
GetFreeCarCheck for Autotrader puts the same data on every Autotrader advert: reliability for the model and year, the faults worth checking, mileage for its age and the car's own MOT history. Free on [Chrome](https://chromewebstore.google.com/detail/iahmdhbnekgofbjjfblhminibekopepe). More: https://getfreecarcheck.co.uk/autotrader-extension

Contains public sector information licensed under the Open Government Licence v3.0, from the DVSA and the VCA.
