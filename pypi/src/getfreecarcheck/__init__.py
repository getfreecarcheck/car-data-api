"""GetFreeCarCheck API client: UK car reliability, MOT faults, mileage, recalls, CO2, ULEZ and car tax from over
100 million DVSA MOT tests. Docs: https://getfreecarcheck.co.uk/developers/docs"""

from __future__ import annotations

import json
import re
import urllib.error
import urllib.parse
import urllib.request
from typing import Any

__version__ = "1.0.1"
BASE = "https://api.getfreecarcheck.co.uk/v1"
__all__ = ["BASE", "GetFreeCarCheck", "ApiError"]


class ApiError(Exception):
    def __init__(self, status: int, body: dict[str, Any]):
        super().__init__(body.get("message") or body.get("error") or f"HTTP {status}")
        self.status, self.body = status, body


def _slug(s: str) -> str:
    return re.sub(r"[^a-z0-9]+", "-", s.lower().strip()).strip("-")


class GetFreeCarCheck:
    """key: only for number plate lookups (free at getfreecarcheck.co.uk/developers)."""

    def __init__(self, key: str | None = None, base: str = BASE, timeout: float = 20):
        self.key, self.base, self.timeout = key, base, timeout

    def get(self, path: str, query: dict[str, Any] | None = None) -> dict[str, Any]:
        q = "?" + urllib.parse.urlencode({k: v for k, v in query.items() if v is not None}) if query else ""
        headers = {"user-agent": f"getfreecarcheck-python/{__version__}", "accept": "application/json"}
        if self.key:
            headers["x-api-key"] = self.key
        req = urllib.request.Request(self.base + path + q, headers=headers)
        try:
            with urllib.request.urlopen(req, timeout=self.timeout) as r:
                return json.load(r)
        except urllib.error.HTTPError as e:
            raise ApiError(e.code, json.loads(e.read() or b"{}")) from None

    def _m(self, kind: str, make: str, model: str) -> dict[str, Any]:
        return self.get(f"/{kind}/{_slug(make)}/{_slug(model)}")

    def models(self) -> dict[str, Any]:
        return self.get("/models")

    def make(self, make: str) -> dict[str, Any]:
        return self.get(f"/models/{_slug(make)}")

    def model(self, make: str, model: str) -> dict[str, Any]:
        return self._m("models", make, model)

    def reliability(self, make: str, model: str) -> dict[str, Any]:
        return self._m("reliability", make, model)

    def defects(self, make: str, model: str) -> dict[str, Any]:
        return self._m("defects", make, model)

    def mileage(self, make: str, model: str) -> dict[str, Any]:
        return self._m("mileage", make, model)

    def recalls(self, make: str, model: str) -> dict[str, Any]:
        return self._m("recalls", make, model)

    def emissions(self, make: str, model: str) -> dict[str, Any]:
        return self._m("emissions", make, model)

    def rankings(self) -> dict[str, Any]:
        return self.get("/rankings")

    def national(self) -> dict[str, Any]:
        return self.get("/national")

    def vehicle(self, reg: str, part: str = "") -> dict[str, Any]:
        """Needs a key. part: "" (vehicle), "mot", "mileage" or "check" (everything)."""
        r = urllib.parse.quote(re.sub(r"\s+", "", reg).upper())
        return self.get(f"/vehicles/{r}" + (f"/{part}" if part else ""))

    def check(self, reg: str) -> dict[str, Any]:
        return self.vehicle(reg, "check")

    def tax(self, first_registered: str, fuel: str, co2: int | None = None, engine_cc: int | None = None, list_price: int | None = None) -> dict[str, Any]:
        return self.get("/tax/ved", {"firstRegistered": first_registered, "fuel": fuel, "co2": co2, "engineCc": engine_cc, "listPrice": list_price})

    def zones(self, first_registered: str, fuel: str, kind: str | None = None) -> dict[str, Any]:
        return self.get("/zones", {"firstRegistered": first_registered, "fuel": fuel, "kind": kind})

    def usage(self) -> dict[str, Any]:
        return self.get("/usage")
