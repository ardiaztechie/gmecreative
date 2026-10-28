import json, colorsys

SRC = "public/images/animasi.json"
DST = "public/images/animasi-orange.json"

HUE_WARM  = 24 / 360   # peach/oranye lembut
HUE_AMBER = 42 / 360   # kuning amber (untuk yang tadinya tosca/cyan)

def remap(rgb):
    r, g, b = rgb[:3]
    h, l, s = colorsys.rgb_to_hls(r, g, b)
    hd = h * 360

    if s < 0.12 or l < 0.03 or l > 0.985:
        return None
    if not (160 <= hd <= 300):
        return None

    new_h = HUE_AMBER if hd < 200 else HUE_WARM

    if l < 0.25:
        new_l, new_s = 0.18 + 0.40 * l, 0.80
    else:
        new_l, new_s = 0.62 + 0.36 * l, 0.90

    nr, ng, nb = colorsys.hls_to_rgb(new_h, new_l, new_s)
    return [nr, ng, nb] + list(rgb[3:])

def walk(node):
    if isinstance(node, dict):
        if node.get("ty") in ("fl", "st") and isinstance(node.get("c"), dict):
            c = node["c"]
            k = c.get("k")
            if c.get("a", 0) == 0 and isinstance(k, list) and len(k) >= 3 \
               and isinstance(k[0], (int, float)):
                new = remap(k)
                if new:
                    c["k"] = new
        for v in node.values():
            walk(v)
    elif isinstance(node, list):
        for v in node:
            walk(v)

with open(SRC, encoding="utf-8") as f:
    data = json.load(f)

walk(data)

with open(DST, "w", encoding="utf-8") as f:
    json.dump(data, f)

print("Selesai ->", DST)