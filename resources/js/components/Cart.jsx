import axios from "axios";
import React from "react";
import toast from "react-hot-toast";
import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";
import { confirmAction } from "../utils/confirmAction";

export default function Cart({ carts, setCartUpdated, cartUpdated }) {
    function increment(id) {
        axios
            .put("/admin/cart/increment", { id })
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

    function decrement(id) {
        axios
            .put("/admin/cart/decrement", { id })
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

    function destroy(id) {
        confirmAction({
            title: "Remove item",
            text: "Are you sure you want to delete this item?",
            confirmText: "Remove",
            variant: "danger",
        }).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            axios
                .put("/admin/cart/delete", { id })
                .then((res) => {
                    setCartUpdated(!cartUpdated);
                    playSound(SuccessSound);
                    toast.success(res?.data?.message);
                })
                .catch((err) => {
                    toast.error(err.response.data.message);
                });
        });
    }

    return (
        <div className="pos-cart-table-wrap">
            {carts.length === 0 ? (
                <div className="pos-empty-state">
                    <i className="fas fa-shopping-cart" aria-hidden="true"></i>
                    <p>No items in cart</p>
                </div>
            ) : (
                <table className="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th aria-label="Remove"></th>
                            <th className="text-right">Price</th>
                            <th className="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {carts.map((item) => (
                            <tr key={item.id}>
                                <td>{item.product.name}</td>
                                <td>
                                    <div className="pos-qty-group">
                                        <button
                                            type="button"
                                            className="pos-qty-btn pos-qty-btn--minus"
                                            onClick={() => decrement(item.id)}
                                        >
                                            <i className="fas fa-minus"></i>
                                        </button>
                                        <span className="pos-qty-value">{item.quantity}</span>
                                        <button
                                            type="button"
                                            className="pos-qty-btn pos-qty-btn--plus"
                                            onClick={() => increment(item.id)}
                                        >
                                            <i className="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        className="pos-qty-btn pos-qty-btn--delete"
                                        onClick={() => destroy(item.id)}
                                        aria-label="Remove item"
                                    >
                                        <i className="fas fa-trash"></i>
                                    </button>
                                </td>
                                <td className="text-right">
                                    {item?.product?.discounted_price}
                                    {item?.product?.price > item?.product?.discounted_price && (
                                        <>
                                            <br />
                                            <del className="text-muted">{item?.product?.price}</del>
                                        </>
                                    )}
                                </td>
                                <td className="text-right">{item?.row_total}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            )}
        </div>
    );
}
