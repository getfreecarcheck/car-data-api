// GetFreeCarCheck API client: UK car reliability, MOT faults, mileage, recalls, CO2, ULEZ and car tax from
// over 100 million DVSA MOT tests. Docs: https://getfreecarcheck.co.uk/developers/docs
export const BASE = "https://api.getfreecarcheck.co.uk/v1";

const slug = (s) => String(s).toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");

export class GetFreeCarCheck {
  /** @param {{ key?: string, base?: string, fetch?: typeof fetch }} [opts] key: only for number plate lookups. */
  constructor(opts = {}) {
    this.key = opts.key;
    this.base = opts.base ?? BASE;
    this.fetch = opts.fetch ?? globalThis.fetch.bind(globalThis);
  }
  async get(path, query) {
    const q = query ? `?${new URLSearchParams(Object.entries(query).filter(([, v]) => v != null).map(([k, v]) => [k, String(v)]))}` : "";
    const r = await this.fetch(`${this.base}${path}${q}`, { headers: this.key ? { "x-api-key": this.key } : {} });
    const body = await r.json();
    if (!r.ok) throw Object.assign(new Error(body.message ?? body.error ?? `HTTP ${r.status}`), { status: r.status, body });
    return body;
  }
  models() { return this.get("/models"); }
  make(make) { return this.get(`/models/${slug(make)}`); }
  model(make, model) { return this.get(`/models/${slug(make)}/${slug(model)}`); }
  reliability(make, model) { return this.get(`/reliability/${slug(make)}/${slug(model)}`); }
  defects(make, model) { return this.get(`/defects/${slug(make)}/${slug(model)}`); }
  mileage(make, model) { return this.get(`/mileage/${slug(make)}/${slug(model)}`); }
  recalls(make, model) { return this.get(`/recalls/${slug(make)}/${slug(model)}`); }
  emissions(make, model) { return this.get(`/emissions/${slug(make)}/${slug(model)}`); }
  rankings() { return this.get("/rankings"); }
  national() { return this.get("/national"); }
  /** Needs a key. part: "" (vehicle), "mot", "mileage" or "check" (everything). */
  vehicle(reg, part = "") { return this.get(`/vehicles/${encodeURIComponent(String(reg).replace(/\s+/g, "").toUpperCase())}${part ? `/${part}` : ""}`); }
  check(reg) { return this.vehicle(reg, "check"); }
  /** Needs a key. { firstRegistered: "YYYY-MM-DD", fuel, co2?, engineCc?, listPrice? } */
  tax(input) { return this.get("/tax/ved", input); }
  /** Needs a key. { firstRegistered: "YYYY-MM-DD", fuel, kind? } */
  zones(input) { return this.get("/zones", input); }
  usage() { return this.get("/usage"); }
}

export default GetFreeCarCheck;
