document.addEventListener('DOMContentLoaded', function() {
    // Only run if on scheme form page
    const totalCost = document.getElementById('total_cost');
    if (!totalCost) return;

    // ELEMENTS
    const el = id => document.getElementById(id);

    const lotteryPercent = el('lottery_percent');
    const lotteryAmount = el('lottery_amount');

    const downPercent = el('down_percent');
    const downAmount = el('down_amount');

    const balanceAmount = el('balance_amount');

    const emiCount = el('emi_count');

    const normalInterest = el('normal_interest');
    const penaltyRate = el('penalty_rate');

    const emiNormal = el('emi_normal');
    const emiPenalty = el('emi_penalty');

    // HELPERS
    const num = value => parseFloat(value) || 0;
    const round = value => Math.ceil(value || 0);

    // EMI FORMULA
    function calculateEMI(principal, annualRate, months) {
        if (months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate <= 0) return round(principal / months);

        const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
        return round(emi);
    }

    function calculateFinancials() {
        const total = num(totalCost?.value);

        // LOTTERY
        const lotteryPer = num(lotteryPercent?.value);
        const lotteryAmt = round((total * lotteryPer) / 100);
        if (lotteryAmount) lotteryAmount.value = lotteryAmt;

        // ALLOTMENT
        const allotmentPer = num(downPercent?.value);
        const allotmentAmt = round((total * allotmentPer) / 100);
        if (downAmount) downAmount.value = allotmentAmt;

        // BALANCE
        const balance = total - lotteryAmt - allotmentAmt;
        if (balanceAmount) balanceAmount.value = round(balance);

        // EMI
        const principal = round(balance);
        const months = num(emiCount?.value);
        const interest = num(normalInterest?.value);
        const penalty = num(penaltyRate?.value);

        if (emiNormal) {
            emiNormal.value = calculateEMI(principal, interest, months);
        }

        if (emiPenalty) {
            emiPenalty.value = calculateEMI(principal, interest + penalty, months);
        }
    }

    // EVENTS
    [
        totalCost,
        lotteryPercent,
        downPercent,
        emiCount,
        normalInterest,
        penaltyRate
    ].forEach(input => {
        input?.addEventListener('input', calculateFinancials);
    });

    // INITIAL LOAD
    calculateFinancials();

    // DYNAMIC DROPDOWNS FOR SCHEME FORM
    const divisionSelect = el('division_id');
    const subDivisionSelect = el('sub_division_id');
    const propertyTypeSelect = el('property_type');
    const propertySubTypeSelect = el('property_sub_type');

    if (divisionSelect) {
        divisionSelect.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const encryptedId = selectedOption?.getAttribute('data-encrypted');
            
            subDivisionSelect.innerHTML = '<option value="">Select Sub Division</option>';
            
            if (!encryptedId) return;
            
            try {
                const response = await fetch(`/get-sub-divisions/${encryptedId}`);
                if (response.ok) {
                    const data = await response.json();
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        subDivisionSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching sub divisions:', error);
            }
        });
    }

    if (propertyTypeSelect) {
        propertyTypeSelect.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const encryptedId = selectedOption?.getAttribute('data-encrypted');
            
            propertySubTypeSelect.innerHTML = '<option value="">Select Sub Type</option>';
            
            if (!encryptedId) return;
            
            try {
                const response = await fetch(`/get-property-sub-types/${encryptedId}`);
                if (response.ok) {
                    const data = await response.json();
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        propertySubTypeSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching property sub types:', error);
            }
        });
    }
});