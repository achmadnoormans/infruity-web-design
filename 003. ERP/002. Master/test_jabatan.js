const fs = require('fs');
const html = fs.readFileSync('007. Jabatan/index.html', 'utf8');

const productsMatch = html.match(/products:\s*(\[[\s\S]*?\]),/);
if (!productsMatch) {
    console.log("Products not found");
    process.exit(1);
}

let products;
try {
    eval('products = ' + productsMatch[1]);
    console.log("Products parsed. Length:", products.length);
} catch (e) {
    console.log("Error parsing products:", e);
}

// Let's also check if Alpine parses the data object correctly.
// Try to extract the whole x-data object.
const match = html.match(/x-data="([^"]+)"/);
console.log("x-data:", match ? match[1] : "not found");

const appFunctionMatch = html.match(/function erpApp\(\) \{\s*return (\{[\s\S]*?\n        \});\n    \}/);
if (appFunctionMatch) {
    try {
        let app = eval('(' + appFunctionMatch[1] + ')');
        console.log("App object parsed! filteredProducts length:", app.filteredProducts.length);
    } catch(e) {
        console.log("App Object parse error", e);
    }
}
