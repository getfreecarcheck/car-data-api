# GetFreeCarCheck car data API and AI-assistant connector

Free UK car data for developers and AI assistants: how reliable each model is (first-time MOT fail rate against the
average car of the same age), the faults it fails on most, typical mileage by age, safety recalls, CO2, ULEZ and car
tax, for over 550 UK models from over 100 million DVSA MOT tests. MOT history by number plate with a free key.

- **Website and free key:** https://getfreecarcheck.co.uk/developers
- **Docs, every field:** https://getfreecarcheck.co.uk/developers/docs
- **REST base:** `https://api.getfreecarcheck.co.uk/v1` (model data: no key, CORS on) · [OpenAPI 3.1](https://api.getfreecarcheck.co.uk/v1/openapi.json)
- **AI-assistant connector (MCP, Streamable HTTP):** `https://api.getfreecarcheck.co.uk/mcp` · registry name `uk.co.getfreecarcheck/car-data`

## Use it in your assistant

1. **Claude:** Settings, Connectors, Add custom connector, then paste `https://api.getfreecarcheck.co.uk/mcp`.
2. **ChatGPT:** Settings, Apps and connectors, Create, then paste the address (developer mode).
3. **Cursor, VS Code and other MCP clients:**

```json
{ "mcpServers": { "getfreecarcheck": { "type": "http", "url": "https://api.getfreecarcheck.co.uk/mcp" } } }
```

Tools: `car_reliability`, `compare_cars`, `most_reliable_cars`, `mileage_check`, `car_safety_recalls`, `ulez_check`,
`road_tax`, `check_vehicle` (number plate check: send your free key as `X-Api-Key`, or add `?key=YOUR_KEY` to the
address), `search` and `fetch`. Every answer carries its source and a link to the full figures.

## Use it in code

```sh
curl https://api.getfreecarcheck.co.uk/v1/reliability/ford/fiesta
npm install getfreecarcheck      # JavaScript and TypeScript: ./npm
pip install getfreecarcheck      # Python: ./pypi
```

## Add a free car check to your website

Paste a box into any page: [build yours](https://getfreecarcheck.co.uk/developers/widget). On WordPress, install the
plugin in [`wordpress/getfreecarcheck`](wordpress/getfreecarcheck) and use `[getfreecarcheck]` or
`[getfreecarcheck_reliability make="ford" model="fiesta"]`.

## Browser extension
GetFreeCarCheck for Autotrader puts the same data on every Autotrader advert: reliability for the model and year, the faults worth checking, mileage for its age and the car's own MOT history. Free on [Chrome](https://chromewebstore.google.com/detail/iahmdhbnekgofbjjfblhminibekopepe). More: https://getfreecarcheck.co.uk/autotrader-extension

## Plans

Model data is free and unlimited. Number plate lookups: Free (500 a month, no card), then paid plans for more
([plans](https://getfreecarcheck.co.uk/developers#plans)). Free plans show "Data: GetFreeCarCheck" with a link
wherever the data appears.

## Licence

Code in this repository: MIT. Our figures: CC BY 4.0. Contains public sector information licensed under the Open
Government Licence v3.0, from the DVSA and the VCA.
