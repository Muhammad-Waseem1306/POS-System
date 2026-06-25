import React, { useCallback, useEffect, useState } from "react";
import Suppliers from "./Suppliers";
import axios from "axios";
import toast, { Toaster } from "react-hot-toast";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { confirmAction } from "../../utils/confirmAction";

export default function Purchase() {
    const [searchTerm, setSearchTerm] = useState("");
    const [barcode, setBarcode] = useState("");
    const [selectedSupplier, setSelectedSupplier] = useState({
        value: 1,
        label: "Own Supplier",
    });
    const [purchaseId, setPurchaseId] = useState(null);
    const [date, setDate] = useState(null);
    const [supplierId, setSupplierId] = useState(null);
    const [tax, setTax] = useState(0);
    const [discount, setDiscount] = useState(0);
    const [shipping, setShipping] = useState(0);
    const [products, setProducts] = useState([]);
    const [searchResults, setSearchResults] = useState([]);
    useEffect(() => {
        const searchParams = new URLSearchParams(window.location.search);
        const barcodeParam = searchParams.get("barcode");
        const purchase_id = searchParams.get("purchase_id");
        if (barcodeParam) {
            setSearchTerm(barcodeParam);
            setBarcode(barcodeParam);
        }
        if (purchase_id) {
            setPurchaseId(purchase_id);
        }
    }, []);
    useEffect(() => {
        if (barcode) {
            getProducts();
        }
    }, [barcode]);
    useEffect(() => {
        if (purchaseId) {
            getPurchaseProducts();
        }
    }, [purchaseId]);
    const getPurchaseProducts = useCallback(async () => {
        try {
            const res = await axios.get(`/admin/purchase/${purchaseId}`);
            const purchaseData = res.data;
            const purchaseProducts = purchaseData?.items?.map((item) => ({
                item_id: item.id,
                id: item.product_id,
                name: item.name,
                price: item.price,
                purchase_price: item.purchase_price,
                stock: item.stock,
                qty: item.quantity,
                subTotal: item.purchase_price * item.quantity,
            }));
            setProducts(purchaseProducts);
            setDate(purchaseData?.date ? purchaseData.date.split(" ")[0] : "");
            setSelectedSupplier({
                value: purchaseData?.supplier_id,
                label: purchaseData?.supplier?.name,
            });
            setTax(purchaseData?.tax);
            setDiscount(purchaseData?.discount_value);
            setShipping(purchaseData?.shipping);
        } catch (error) {
            console.error("Error fetching products:", error);
        } finally {
        }
    }, [purchaseId]);

    const getProducts = useCallback(async () => {
        if (!searchTerm.trim()) {
            console.log("Search term is empty");
            return;
        }

        // Optional: Uncomment if you want to show loading state
        // setLoading(true);

        try {
            const res = await axios.get("/admin/products", {
                params: { search: searchTerm },
            });

            const productsData = res.data;

            // Ensure productsData and productsData.data exist
            if (productsData?.data && productsData.data.length) {
                productsData.data.forEach((product) => {
                    const existingProductIndex = products.findIndex(
                        (p) => p.id === product.id
                    );
                    if (existingProductIndex !== -1) {
                        // Product exists, increment qty
                        setProducts((prevProducts) => {
                            const updatedProducts = [...prevProducts];
                            updatedProducts[existingProductIndex].qty += 1; // Increment qty
                            updatedProducts[existingProductIndex].subTotal =
                                updatedProducts[existingProductIndex]
                                    .purchase_price *
                                updatedProducts[existingProductIndex].qty; // Update subTotal
                            return updatedProducts;
                        });
                    } else {
                        // New product, add to the list
                        const newProduct = {
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            purchase_price: product.purchase_price,
                            stock: product.quantity,
                            qty: 1,
                            subTotal: product.purchase_price,
                        };
                        setProducts((prevProducts) => [
                            ...prevProducts,
                            newProduct,
                        ]);
                    }
                });
            }
        } catch (error) {
            console.error("Error fetching products:", error);
        } finally {
            // Optional: Uncomment if you want to hide loading state
            // setLoading(false);

            // Clear searchTerm if needed
            setSearchTerm("");
        }
    }, [searchTerm]); // Don't forget to add searchTerm as a dependency

    // Handle deletion of a product
    const handleDelete = (id) => {
        setProducts(products.filter((product) => product.id !== id));
    };

    // Update quantity and recalculate subtotal
    const handleQtyChange = (id, value) => {
        const updatedProducts = products.map((product) => {
            if (product.id === id) {
                const newQty = parseInt(value) || 0;
                return {
                    ...product,
                    qty: newQty,
                    subTotal: parseFloat(
                        (product.purchase_price * newQty).toFixed(2)
                    ),
                };
            }
            return product;
        });
        setProducts(updatedProducts);
    };

    // Update purchase price and recalculate subtotal
    const handlePriceChange = (id, value) => {
        const updatedProducts = products.map((product) => {
            if (product.id === id) {
                const newPrice = parseFloat(value) || 0;
                return {
                    ...product,
                    purchase_price: newPrice,
                    subTotal: parseFloat((product.qty * newPrice).toFixed(2)),
                };
            }
            return product;
        });
        setProducts(updatedProducts);
    };
    // Add a new product by searching
    const handleSearchAdd = () => {
        getProducts();
    };

    // Calculate totals with two decimal places
    const calculateTotals = () => {
        const subTotal = products.reduce(
            (sum, product) => sum + product.subTotal,
            0
        );
        const formattedSubTotal = parseFloat(subTotal.toFixed(2));
        const formattedTax = parseFloat((tax || 0).toFixed(2));
        const formattedDiscount = parseFloat((discount || 0).toFixed(2));
        const formattedShipping = parseFloat((shipping || 0).toFixed(2));
        const grandTotal = parseFloat(
            (
                formattedSubTotal +
                formattedTax -
                formattedDiscount +
                formattedShipping
            ).toFixed(2)
        );

        return {
            subTotal: formattedSubTotal,
            tax: formattedTax,
            discount: formattedDiscount,
            shipping: formattedShipping,
            grandTotal,
        };
    };

    const totals = calculateTotals();
    const handleSubmit = async () => {
        if (totals.grandTotal <= 0) {
            //    toast.error("Total must be greater than zero.");
            return;
        }
        if (!date) {
            toast.error("Please select purchase date.");
            return;
        }
        if (!supplierId) {
            toast.error("Please select a supplier.");
            return;
        }

        confirmAction({
            title: "Save purchase",
            text: "Are you sure you want to save this purchase?",
            confirmText: "Save purchase",
        }).then(async (confirmed) => {
            if (!confirmed) {
                return;
            }

            try {
                    const res = await axios.post("/admin/purchase", {
                        purchase_id: purchaseId,
                        date,
                        products,
                        supplierId,
                        totals,
                    });
                    setProducts([]);
                    toast.success(res?.data?.message);
                    window.location.href = "/admin/purchase";
                } catch (err) {
                    toast.error(
                        err.response?.data?.message || "An error occurred"
                    );
                }
        });
    };

    // product search
    useEffect(() => {
        // Define the asynchronous function
        async function getProducts() {
            if (!searchTerm.trim()) {
                setSearchResults([]);
                return;
            }

            try {
                const res = await axios.get("/admin/products", {
                    params: { search: searchTerm },
                });

                const productsData = res.data;
                setSearchResults(productsData?.data || []);
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        }
        // Call the async function inside useEffect
        getProducts();
    }, [searchTerm]);
    // Handle adding selected product to the products list
    // Handle adding selected product to the products list
    const handleProductSelect = (product) => {
        const existingProductIndex = products.findIndex(
            (p) => p.id === product.id
        );

        if (existingProductIndex !== -1) {
            // If product exists, increment quantity
            setProducts((prevProducts) => {
                const updatedProducts = [...prevProducts];
                updatedProducts[existingProductIndex].qty += 1;
                updatedProducts[existingProductIndex].subTotal =
                    updatedProducts[existingProductIndex].purchase_price *
                    updatedProducts[existingProductIndex].qty;
                return updatedProducts;
            });
        } else {
            // Add new product to the list
            const newProduct = {
                id: product.id,
                name: product.name,
                price: product.price,
                purchase_price: product.purchase_price,
                stock: product.quantity,
                qty: 1,
                subTotal: product.purchase_price,
            };
            setProducts((prevProducts) => [...prevProducts, newProduct]);
        }

        // Clear search term and results
        setSearchTerm("");
        setSearchResults([]);
    };
    return (
        <>
            <div className="pos-shell">
                <div className="content-card">
                    <div className="pos-section__header">Purchase Details</div>
                    <div className="pos-section__body">
                        <div className="row">
                            <div className="mb-3 col-md-6">
                                <label htmlFor="date" className="form-label">
                                    Purchase Date
                                    <span className="text-danger">*</span>
                                </label>
                                <DatePicker
                                    name="date"
                                    className="form-control"
                                    placeholderText="Enter purchase date"
                                    selected={date ? new Date(date) : null}
                                    dateFormat="yyyy-MM-dd"
                                    popperProps={{ strategy: "fixed" }}
                                    popperPlacement="bottom-start"
                                    onChange={(d) => {
                                        const formattedDate = d
                                            ? d.toISOString().split("T")[0]
                                            : null;
                                        setDate(formattedDate);
                                    }}
                                />
                            </div>
                            <div className="mb-3 col-md-6">
                                <label htmlFor="supplier" className="form-label">
                                    Supplier
                                    <span className="text-danger">*</span>
                                </label>
                                <Suppliers
                                    setSupplierId={setSupplierId}
                                    oldSupplier={selectedSupplier}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div className="content-card">
                    <div className="pos-section__header">Line Items</div>
                    <div className="pos-section__body">
                        <div className="pos-search-bar mb-3">
                            <div className="pos-search-bar__field" style={{ flex: 2 }}>
                                <span className="pos-search-bar__icon">
                                    <i className="fas fa-search"></i>
                                </span>
                                <input
                                    type="search"
                                    className="form-control"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    placeholder="Enter product barcode/name"
                                />
                            </div>
                            <button
                                type="button"
                                className="btn btn-modern btn-modern--primary btn-modern--sm"
                                onClick={handleSearchAdd}
                            >
                                Add Product
                            </button>
                        </div>

                        {searchResults.length > 0 && (
                            <ul className="pos-search-results mb-3">
                                {searchResults.map((product) => (
                                    <li
                                        key={product.id}
                                        className="pos-search-results__item"
                                        onClick={() => handleProductSelect(product)}
                                    >
                                        {product.name} — {product.price}
                                    </li>
                                ))}
                            </ul>
                        )}

                        <div className="table-responsive">
                            <table className="table table-modern text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Purchase Price</th>
                                        <th>Current Stock</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {products.map((product, index) => (
                                        <tr key={product.id}>
                                            <td>{index + 1}</td>
                                            <td>{product.name}</td>
                                            <td>
                                                <input
                                                    type="number"
                                                    min="1"
                                                    className="form-control form-control-sm mx-auto"
                                                    style={{ maxWidth: "6rem" }}
                                                    value={product.purchase_price}
                                                    onChange={(e) =>
                                                        handlePriceChange(product.id, e.target.value)
                                                    }
                                                />
                                            </td>
                                            <td>{product.stock}</td>
                                            <td>
                                                <input
                                                    type="number"
                                                    min="1"
                                                    className="form-control form-control-sm mx-auto"
                                                    style={{ maxWidth: "5rem" }}
                                                    value={product.qty}
                                                    onChange={(e) =>
                                                        handleQtyChange(product.id, e.target.value)
                                                    }
                                                />
                                            </td>
                                            <td>{product.subTotal.toFixed(2)}</td>
                                            <td>
                                                <button
                                                    type="button"
                                                    className="pos-qty-btn pos-qty-btn--delete"
                                                    onClick={() => handleDelete(product.id)}
                                                    title="Remove"
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="row mt-3">
                            <div className="col-md-6 offset-md-6">
                                <table className="table pos-totals-table">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td>{totals.subTotal.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax:</th>
                                            <td>{totals.tax.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount:</th>
                                            <td>{totals.discount.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping:</th>
                                            <td>{totals.shipping.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Grand Total:</th>
                                            <td>{totals.grandTotal.toFixed(2)}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="content-card">
                    <div className="pos-section__header">Adjustments</div>
                    <div className="pos-section__body">
                        <div className="row">
                            <div className="mb-3 col-md-4">
                                <label htmlFor="tax" className="form-label">Tax</label>
                                <input
                                    type="number"
                                    className="form-control"
                                    value={tax}
                                    min="0"
                                    onChange={(e) => setTax(parseFloat(e.target.value) || 0)}
                                    placeholder="Enter tax"
                                    name="tax"
                                />
                            </div>
                            <div className="mb-3 col-md-4">
                                <label htmlFor="discount" className="form-label">Discount</label>
                                <input
                                    type="number"
                                    min="0"
                                    className="form-control"
                                    value={discount}
                                    onChange={(e) => setDiscount(parseFloat(e.target.value) || 0)}
                                    placeholder="Enter discount"
                                    name="discount"
                                />
                            </div>
                            <div className="mb-3 col-md-4">
                                <label htmlFor="shipping" className="form-label">Shipping Charge</label>
                                <input
                                    type="number"
                                    min="0"
                                    className="form-control"
                                    value={shipping}
                                    onChange={(e) => setShipping(parseFloat(e.target.value) || 0)}
                                    placeholder="Enter shipping"
                                    name="shipping"
                                />
                            </div>
                        </div>
                    </div>
                    <div className="pos-footer">
                        <button
                            type="button"
                            className="btn btn-modern btn-modern--primary"
                            onClick={handleSubmit}
                        >
                            {purchaseId ? "Update Purchase" : "Create Purchase"}
                        </button>
                    </div>
                </div>
            </div>

            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}
