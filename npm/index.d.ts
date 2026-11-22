export declare const BASE: string;
export type Answer<T = Record<string, unknown>> = { data: T; meta: { sources: unknown[]; licenceLine: string; attribution: string; page: string; citation: string; [k: string]: unknown } };
export declare class GetFreeCarCheck {
  constructor(opts?: { key?: string; base?: string; fetch?: typeof fetch });
  get(path: string, query?: Record<string, string | number | null | undefined>): Promise<Answer>;
  models(): Promise<Answer>;
  make(make: string): Promise<Answer>;
  model(make: string, model: string): Promise<Answer>;
  reliability(make: string, model: string): Promise<Answer>;
  defects(make: string, model: string): Promise<Answer>;
  mileage(make: string, model: string): Promise<Answer>;
  recalls(make: string, model: string): Promise<Answer>;
  emissions(make: string, model: string): Promise<Answer>;
  rankings(): Promise<Answer>;
  national(): Promise<Answer>;
  vehicle(reg: string, part?: "" | "mot" | "mileage" | "check"): Promise<Answer>;
  check(reg: string): Promise<Answer>;
  tax(input: { firstRegistered: string; fuel: string; co2?: number; engineCc?: number; listPrice?: number }): Promise<Answer>;
  zones(input: { firstRegistered: string; fuel: string; kind?: "car" | "van" | "motorcycle" }): Promise<Answer>;
  usage(): Promise<Answer>;
}
export default GetFreeCarCheck;
