<?php
$page_title = 'Billing Manager';
include 'inc.php'; 

// ইনভয়েস ডাটা ফেচ করা
$sql = "SELECT * FROM billing_invoices WHERE sccode = '$sccode' ORDER BY invoice_date DESC";
$result = $conn->query($sql);
?>

<style>
    :root {
        --m3-surface: #FDFBFF;
        --m3-primary-container: #EADDFF;
        --m3-on-primary-container: #21005D;
        --m3-secondary-container: #E8DEF8;
        --m3-tertiary-container: #FFD8E4;
        --m3-outline: #79747E;
        --status-paid: #C2EFAD;
        --status-partial: #FFDCC3;
        --status-unpaid: #F9DEDC;
    }

    body { background-color: var(--m3-surface); font-family: 'Roboto', sans-serif; margin: 0; padding: 0; }
    .container { padding: 16px; max-width: 600px; margin: 0 auto; }

    /* Summary Card */
    .summary-card {
        background-color: var(--m3-primary-container);
        color: var(--m3-on-primary-container);
        border-radius: 28px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Invoice List Item */
    .invoice-card {
        background: #FFFFFF;
        border: 1px solid var(--m3-outline);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        transition: 0.2s;
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .invoice-card:active { background-color: var(--m3-secondary-container); transform: scale(0.98); }

    .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
    .inv-no { font-weight: 700; color: var(--m3-primary); font-size: 14px; }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-paid { background: var(--status-paid); color: #062100; }
    .status-partial { background: var(--status-partial); color: #301400; }
    .status-unpaid { background: var(--status-unpaid); color: #410E0B; }

    .amount-val { font-size: 20px; font-weight: 700; color: #1C1B1F; }
    .amount-label { font-size: 11px; color: #49454F; text-transform: uppercase; }

    .details-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; border-top: 1px dashed #DDD; padding-top: 10px; }
</style>

<main class="container">
    <div class="summary-card">
        <div>
            <div style="font-size: 14px; opacity: 0.8;">Billing Overview</div>
            <div style="font-size: 24px; font-weight: 700;">Invoices</div>
        </div>
        <i class="bi bi-receipt fs-1"></i>
    </div>

    <div style="font-size: 12px; font-weight: 500; color: var(--m3-outline); margin: 0 0 12px 4px;">RECENT INVOICES</div>

    <?php while($row = $result->fetch_assoc()): 
        $status_class = 'status-' . $row['payment_status'];
    ?>
        <a href="billing-details.php?id=<?php echo $row['id']; ?>" class="invoice-card">
            <div class="header-row">
                <div>
                    <div class="inv-no"><?php echo $row['invoice_no']; ?></div>
                    <div style="font-size: 12px; color: gray;"><?php echo $row['customer_name']; ?></div>
                </div>
                <span class="status-badge <?php echo $status_class; ?>">
                    <?php echo $row['payment_status']; ?>
                </span>
            </div>

            <div class="details-row">
                <div>
                    <div class="amount-label">Grand Total</div>
                    <div class="amount-val">৳<?php echo number_format($row['grand_total'], 2); ?></div>
                </div>
                <div style="text-align: right;">
                    <div class="amount-label" style="color: #B3261E;">Due Amount</div>
                    <div class="amount-val" style="color: #B3261E;">৳<?php echo number_format($row['due_amount'], 2); ?></div>
                </div>
            </div>
            
            <div style="font-size: 11px; margin-top: 8px; color: #79747E;">
                <i class="bi bi-calendar-event"></i> Date: <?php echo date('d M, Y', strtotime($row['invoice_date'])); ?>
            </div>
        </a>
    <?php endwhile; ?>
</main>

<div style="height: 5px;"></div>
<?php include 'footer.php'; ?>