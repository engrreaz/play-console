<style>
    /* Loading Container */
.m3-loader-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
}

/* Material 3 Circular Spinner */
.m3-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--m3-primary-tonal);
    border-top: 4px solid var(--m3-primary);
    border-radius: 50%;
    animation: m3-spin 0.8s linear infinite;
}

@keyframes m3-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Pulse Animation for Text */
.m3-loading-text {
    margin-top: 16px;
    font-weight: 800;
    color: var(--m3-primary);
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.75rem;
    animation: m3-pulse 1.5s ease-in-out infinite;
}

@keyframes m3-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.95); }
}

/* Skeleton Shimmer (For cards) */
.m3-skeleton {
    background: #eee;
    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
    border-radius: 8px;
    background-size: 200% 100%;
    animation: 1.5s m3-shimmer linear infinite;
}

@keyframes m3-shimmer {
    to { background-position-x: -200%; }
}
</style>


<div class="m3-card border-0 shadow-sm p-3">
    <div class="d-flex align-items-center mb-3">
        <div class="m3-skeleton" style="width: 50px; height: 50px; border-radius: 12px;"></div>
        <div class="ms-3 flex-grow-1">
            <div class="m3-skeleton mb-2" style="width: 60%; height: 15px;"></div>
            <div class="m3-skeleton" style="width: 40%; height: 10px;"></div>
        </div>
    </div>
    <div class="m3-skeleton" style="width: 100%; height: 20px; border-radius: 100px;"></div>
</div>