import React, { useEffect, useState } from 'react';
import axios from 'axios';

const GuarantorSelect = ({ customerId, setGuarantorId }) => {
    const [guarantors, setGuarantors] = useState([]);
    const [selected, setSelected] = useState('');

    useEffect(() => {
        setGuarantors([]);
        setSelected('');
        if (!customerId) return;
        axios.get(`/admin/get/customers/${customerId}/guarantors`).then(res => {
            setGuarantors(res.data || []);
        }).catch(err => {
            console.error('Error fetching guarantors', err);
        });
    }, [customerId]);

    useEffect(() => {
        setGuarantorId(selected || null);
    }, [selected]);

    return (
        <select className="form-control form-control-sm" value={selected} onChange={e => setSelected(e.target.value)}>
            <option value="">Select guarantor</option>
            {guarantors.map(g => (
                <option key={g.id} value={g.id}>{g.name} - {g.phone} - {g.cnic ?? ''}</option>
            ))}
        </select>
    );
};

export default GuarantorSelect;
