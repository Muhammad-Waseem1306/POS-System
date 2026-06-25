import React, {useEffect, useState, useCallback } from "react";
import axios from "axios";
import Cart from "./Cart";
import toast, { Toaster } from "react-hot-toast";
import CustomerSelect from "./CutomerSelect";
import GuarantorSelect from "./GuarantorSelect";
import { confirmAction } from "../utils/confirmAction";

import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";

export default function Pos() {
    const [products, setProducts] = useState([]);
    const [carts, setCarts] = useState([]);
    const [orderDiscount, setOrderDiscount] = useState(0);
    const [paid, setPaid] = useState(0);
    const [due, setDue] = useState(0);
    const [total, setTotal] = useState(0);
    const [updateTotal, setUpdateTotal] = useState(0);
    const [customerId, setCustomerId] = useState();
    const [saleType, setSaleType] = useState('cash');
    const [installmentMonths, setInstallmentMonths] = useState(6);
    const [selectedGuarantorId, setSelectedGuarantorId] = useState(null);
    const [cartUpdated, setCartUpdated] = useState(false);
    const [productUpdated, setProductUpdated] = useState(false);
    const [searchQuery, setSearchQuery] = useState("");
    const [searchBarcode, setSearchBarcode] = useState("");
    const { protocol, hostname, port } = window.location;
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(0);
    const [loading, setLoading] = useState(false);
    const fullDomainWithPort = `${protocol}//${hostname}${
        port ? `:${port}` : ""
    }`;
    const getProducts = useCallback(
        async (search = "", page = 1, barcode = "") => {
            setLoading(true);
            try {
                const res = await axios.get('/admin/get/products', {
                    params: { search, page, barcode },
                });
                const productsData = res.data;
                setProducts((prev) => [...prev, ...productsData.data]); // Append new products
                if (productsData.data.length === 1 && barcode != "") {
                    addProductToCart(productsData.data[0].id);
                    getCarts();
                }
                setTotalPages(productsData.meta.last_page); // Get total pages
            } catch (error) {
                console.error("Error fetching products:", error);
            } finally {
                setLoading(false); // Set loading to false
            }
        },
        []
    );
    const getUpdatedProducts = useCallback(async () => {
        try {
            const res = await axios.get('/admin/get/products');
            const productsData = res.data;
            setProducts(productsData.data);
            setTotalPages(productsData.meta.last_page); // Get total pages
        } catch (error) {
            console.error("Error fetching products:", error);
        }
    }, []);
    useEffect(() => {
        getUpdatedProducts();
    }, [productUpdated]);

    const getCarts = async () => {
        try {
            const res = await axios.get('/admin/cart');
            const data = res.data;
            setTotal(data?.total);
            setUpdateTotal(data?.total - orderDiscount);
            setCarts(data?.carts);
        } catch (error) {
            console.error("Error fetching carts:", error);
        }
    };

    useEffect(() => {
        getCarts();
    }, []);

    useEffect(() => {
        getCarts();
    }, [cartUpdated]);

    useEffect(() => {
        let paid1 = paid;
        let disc = orderDiscount;
        if (paid == "") {
            paid1 = 0;
        }
        if (orderDiscount == "") {
            disc = 0;
        }
        const updatedTotalAmount = parseFloat(total) - parseFloat(disc);
        const dueAmount = updatedTotalAmount - parseFloat(paid1);
        setUpdateTotal(updatedTotalAmount?.toFixed(2));
        setDue(dueAmount?.toFixed(2));
    }, [orderDiscount, paid, total]);
    useEffect(() => {
        if (searchQuery) {
            setProducts([]);
            getProducts(searchQuery, currentPage, "");
        }
        setSearchBarcode("");
    }, [currentPage, searchQuery]);

    useEffect(() => {
        if (searchBarcode) {
            setProducts([]);
           getProducts("", currentPage, searchBarcode);
        }
    }, [searchBarcode]);

    // Infinite scroll logic
    useEffect(() => {
        const handleScroll = () => {
            if (
                window.innerHeight + document.documentElement.scrollTop >=
                document.documentElement.offsetHeight
            ) {
                // Load next page if not on the last page
                if (currentPage < totalPages) {
                    setCurrentPage((prev) => prev + 1);
                }
            }
        };

        window.addEventListener("scroll", handleScroll);
        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, [currentPage, totalPages]);

    function addProductToCart(id) {
        axios
            .post("/admin/cart", { id })
            .then((res) => {
                setCartUpdated(!cartUpdated);
                playSound(SuccessSound);
                toast.success(res?.data?.message);
            })
            .catch((err) => {
                playSound(WarningSound);
                toast.error(err.response.data.message);
            });
    }
    function cartEmpty() {
        if (total <= 0) {
            return;
        }
        confirmAction({
            title: "Clear cart",
            text: "Are you sure you want to delete the cart?",
            confirmText: "Clear cart",
            variant: "danger",
        }).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            axios
                .put("/admin/cart/empty")
                .then((res) => {
                    setCartUpdated(!cartUpdated);
                    playSound(SuccessSound);
                    toast.success(res?.data?.message);
                })
                .catch((err) => {
                    playSound(WarningSound);
                    toast.error(err.response.data.message);
                });
        });
    }
    function orderCreate() {
        if (total <= 0) {
            return;
        }
        if (!customerId) {
            toast.error("Please select customer");
            return;
        }
        confirmAction({
            title: "Complete order",
            html: `Are you sure you want to complete this order?<br><strong>Due: ${due}</strong>`,
            confirmText: "Complete order",
        }).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            axios
                .put("/admin/order/create", {
                    customer_id: customerId,
                    order_discount: parseFloat(orderDiscount) || 0,
                    paid: parseFloat(paid) || 0,
                    sale_type: saleType,
                    installment_months: saleType === 'installment' ? installmentMonths : undefined,
                    guarantor_id: saleType === 'installment' ? selectedGuarantorId : undefined,
                })
                .then((res) => {
                    setCartUpdated(!cartUpdated);
                    setProductUpdated(!productUpdated);
                    toast.success(res?.data?.message);
                    window.location.href = `orders/pos-invoice/${res?.data?.order?.id}`;
                })
                .catch((err) => {
                    toast.error(err.response.data.message);
                });
        });
    }
    return (
        <>
            <div className="pos-layout">
                <aside className="pos-layout__cart">
                    <div className="content-card">
                        <div className="pos-section__header">Customer</div>
                        <div className="pos-section__body">
                            <CustomerSelect setCustomerId={setCustomerId} />
                        </div>
                    </div>

                    <div className="content-card pos-cart-card">
                        <div className="pos-section__header">Cart</div>
                        <div className="pos-section__body pos-section__body--flush">
                            <Cart
                                carts={carts}
                                setCartUpdated={setCartUpdated}
                                cartUpdated={cartUpdated}
                            />
                        </div>
                    </div>

                    <div className="content-card pos-summary-card">
                        <div className="pos-section__header">Payment</div>
                        <div className="pos-section__body pos-section__body--compact">
                            <div className="pos-field-row">
                                <span className="pos-field-row__label">Sub Total</span>
                                <span className="pos-field-row__value">{total}</span>
                            </div>
                            <div className="pos-field-row">
                                <span className="pos-field-row__label">Sale Type</span>
                                <div className="pos-field-row__value">
                                    <select
                                        className="form-control form-control-sm"
                                        value={saleType}
                                        onChange={(e) => setSaleType(e.target.value)}
                                        disabled={total <= 0}
                                    >
                                        <option value="cash">Cash</option>
                                        <option value="installment">Installment</option>
                                    </select>
                                </div>
                            </div>
                            {saleType === "installment" && (
                                <>
                                    <div className="pos-field-row">
                                        <span className="pos-field-row__label">Guarantor</span>
                                        <div className="pos-field-row__value">
                                            <GuarantorSelect
                                                customerId={customerId}
                                                setGuarantorId={setSelectedGuarantorId}
                                            />
                                        </div>
                                    </div>
                                    <div className="pos-field-row">
                                        <span className="pos-field-row__label">Installment Months</span>
                                        <div className="pos-field-row__value">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="Months"
                                                min={1}
                                                disabled={total <= 0}
                                                value={installmentMonths}
                                                onChange={(e) =>
                                                    setInstallmentMonths(Number(e.target.value) || 1)
                                                }
                                            />
                                        </div>
                                    </div>
                                    <div className="pos-field-row">
                                        <span className="pos-field-row__label">Down Payment</span>
                                        <div className="pos-field-row__value">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="Enter down payment"
                                                min={0}
                                                max={updateTotal}
                                                disabled={total <= 0}
                                                value={paid}
                                                onChange={(e) => {
                                                    const value = e.target.value;
                                                    if (
                                                        parseFloat(value) < 0 ||
                                                        parseFloat(value) > updateTotal
                                                    ) {
                                                        return;
                                                    }
                                                    setPaid(value);
                                                }}
                                            />
                                        </div>
                                    </div>
                                </>
                            )}
                            <div className="pos-field-row">
                                <span className="pos-field-row__label">Discount</span>
                                <div className="pos-field-row__value">
                                    <input
                                        type="number"
                                        className="form-control form-control-sm"
                                        placeholder="Enter discount"
                                        min={0}
                                        disabled={total <= 0}
                                        value={orderDiscount}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            if (parseFloat(value) > total || parseFloat(value) < 0) {
                                                return;
                                            }
                                            setOrderDiscount(value);
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="pos-field-row pos-field-row--checkbox">
                                <label className="pos-field-row__check">
                                    <input
                                        type="checkbox"
                                        disabled={total <= 0}
                                        onChange={(e) => {
                                            if (e.target.checked) {
                                                const fractionalPart = total % 1;
                                                setOrderDiscount(fractionalPart?.toFixed(2));
                                            } else {
                                                setOrderDiscount(0);
                                            }
                                        }}
                                    />
                                    <span>Apply fractional discount</span>
                                </label>
                            </div>
                            <div className="pos-field-row pos-field-row--total">
                                <span className="pos-field-row__label">Total</span>
                                <span className="pos-field-row__value">{updateTotal}</span>
                            </div>
                            {saleType !== "installment" && (
                                <div className="pos-field-row">
                                    <span className="pos-field-row__label">Paid</span>
                                    <div className="pos-field-row__value">
                                        <input
                                            type="number"
                                            className="form-control form-control-sm"
                                            placeholder="Enter paid"
                                            min={0}
                                            disabled={total <= 0}
                                            value={paid}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                if (
                                                    parseFloat(value) < 0 ||
                                                    parseFloat(value) > updateTotal
                                                ) {
                                                    return;
                                                }
                                                setPaid(value);
                                            }}
                                        />
                                    </div>
                                </div>
                            )}
                            <div className="pos-field-row pos-field-row--due">
                                <span className="pos-field-row__label">Due</span>
                                <span className="pos-field-row__value">{due}</span>
                            </div>
                        </div>
                        <div className="pos-footer pos-actions">
                            <button
                                onClick={() => cartEmpty()}
                                type="button"
                                className="btn btn-modern btn-modern--ghost"
                            >
                                Clear Cart
                            </button>
                            <button
                                onClick={() => orderCreate()}
                                type="button"
                                className="btn btn-modern btn-modern--primary"
                            >
                                Checkout
                            </button>
                        </div>
                    </div>
                </aside>

                <main className="pos-layout__products">
                    <div className="content-card pos-products-card">
                        <div className="pos-section__header">Products</div>
                        <div className="pos-section__body">
                            <div className="pos-search-row">
                                <div className="pos-search-bar">
                                    <div className="pos-search-bar__field">
                                        <span className="pos-search-bar__icon">
                                            <i className="fas fa-barcode"></i>
                                        </span>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Scan or enter barcode"
                                            value={searchBarcode}
                                            autoFocus
                                            onChange={(e) => setSearchBarcode(e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="pos-search-bar">
                                    <div className="pos-search-bar__field">
                                        <span className="pos-search-bar__icon">
                                            <i className="fas fa-search"></i>
                                        </span>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Search by product name"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className="row products-card-container">
                                {products.length > 0 ? (
                                    products.map((product, index) => (
                                        <div
                                            onClick={() => addProductToCart(product.id)}
                                            className="col-6 col-md-4 col-lg-3 mb-3 pos-product-col"
                                            key={index}
                                            role="button"
                                            tabIndex={0}
                                            onKeyDown={(e) => {
                                                if (e.key === "Enter" || e.key === " ") {
                                                    addProductToCart(product.id);
                                                }
                                            }}
                                        >
                                            <div className="pos-product-card text-center">
                                                <img
                                                    src={`${fullDomainWithPort}/storage/${product.image}`}
                                                    alt={product.name}
                                                    className="img-thumb"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = `${fullDomainWithPort}/assets/images/no-image.png`;
                                                    }}
                                                    width={120}
                                                    height={100}
                                                />
                                                <div className="product-details">
                                                    <p className="product-name">
                                                        {product.name} ({product.quantity})
                                                    </p>
                                                    <p>Price: {product?.discounted_price}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="col-12">
                                        <div className="pos-empty-state pos-empty-state--products">
                                            <i className="fas fa-box-open" aria-hidden="true"></i>
                                            <p>Search or scan to add products</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                            {loading && <div className="loading-more">Loading more…</div>}
                        </div>
                    </div>
                </main>
            </div>
            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}