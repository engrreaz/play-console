<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 8px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-primary">Split Payment</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted small mb-3">Enter the amount to split. The rest will remain as dues.</p>
                
                <input type="hidden" id="spltid">
                <input type="hidden" id="spltamtpre">
                
                <div class="form-floating mb-2">
                    <input type="number" class="form-control" id="spltamt" placeholder="Amount" style="border-radius: 8px; font-weight: 700;">
                    <label for="spltamt">Enter Amount to Pay Now</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-primary" id="mybtn" onclick="splitable();" style="border-radius: 8px; font-weight: 700;">Split Now</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 8px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-danger">Add Fine / Misc</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control text-danger fw-bold" id="fine_amount" placeholder="Amount" style="border-radius: 8px; font-size: 1.2rem;">
                    <label for="fine_amount">Fine Amount (BDT)</label>
                </div>
                <div class="form-floating" hidden>
                    <textarea class="form-control" id="fine_note" placeholder="Note" style="height: 100px; border-radius: 8px;"></textarea>
                    <label for="fine_note">Note (Optional)</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <div id="history"></div>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">Back</button>
                <button type="button" class="btn btn-warning fw-bold shadow-sm" onclick="saveFine();" style="border-radius: 8px;">Add Fine</button>
            </div>
        </div>
    </div>
</div>