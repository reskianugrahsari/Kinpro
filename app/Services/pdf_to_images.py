import sys
import os
import json
import pymupdf


def render_pdf(pdf_path, output_dir, max_pages=10, dpi=150):
    if not os.path.isfile(pdf_path):
        print(json.dumps({"status": "error", "message": "PDF file not found", "pages": []}))
        return 1

    os.makedirs(output_dir, exist_ok=True)
    doc = pymupdf.open(pdf_path)
    total = min(len(doc), max_pages)
    pages = []

    for i in range(total):
        page = doc[i]
        pix = page.get_pixmap(dpi=dpi)
        out_path = os.path.join(output_dir, f"page_{i + 1:03d}.png")
        pix.save(out_path)
        pages.append(out_path)

    doc.close()
    print(json.dumps({"status": "ok", "pages": pages}))
    return 0


if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({"status": "error", "message": "Usage: pdf_to_images.py <pdf_path> <output_dir> [max_pages] [dpi]", "pages": []}))
        sys.exit(1)

    pdf_file = sys.argv[1]
    out_dir = sys.argv[2]
    max_p = int(sys.argv[3]) if len(sys.argv) > 3 else 10
    dpi_val = int(sys.argv[4]) if len(sys.argv) > 4 else 150
    sys.exit(render_pdf(pdf_file, out_dir, max_p, dpi_val))
