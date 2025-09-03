<?php $__env->startSection('title', 'Admin Dashboard - Crafters\' Corner'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('styles'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Dashboard specific styles */
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .dashboard-header h1 {
        color: #2c3e50;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .breadcrumb {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stat-card.customers::before {
        background: #3498db;
    }
    
    .stat-card.orders::before {
        background: #2ecc71;
    }
    
    .stat-card.pending::before {
        background: #f39c12;
    }
    
    .stat-card.requests::before {
        background: #e74c3c;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #7f8c8d;
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .stat-link {
        color: #3498db;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s;
    }
    
    .stat-link:hover {
        color: #2980b9;
    }
    
    .chart-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }
    
    .chart-header {
        margin-bottom: 2rem;
    }
    
    .chart-header h3 {
        color: #2c3e50;
        font-size: 1.5rem;
    }
    
    #earningsChart {
        max-height: 400px;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-number {
            font-size: 2rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <div class="breadcrumb">Home / Dashboard</div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card customers">
        <div class="stat-number"><?php echo e($totalCustomers); ?></div>
        <div class="stat-label">Total Customers</div>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="stat-link">More info →</a>
    </div>
    
    <div class="stat-card orders">
        <div class="stat-number"><?php echo e($todayOrders); ?></div>
        <div class="stat-label">Today Orders</div>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="stat-link">More info →</a>
    </div>
    
    <div class="stat-card pending">
        <div class="stat-number"><?php echo e($pendingOrders); ?></div>
        <div class="stat-label">Pending Orders</div>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'pending'])); ?>" class="stat-link">More info →</a>
    </div>
    
    <div class="stat-card requests">
        <div class="stat-number"><?php echo e($customerRequests); ?></div>
        <div class="stat-label">Customer Requests</div>
        <a href="#" class="stat-link">More info →</a>
    </div>
</div>

<!-- Cart Statistics -->
<div class="stats-grid">
    <div class="stat-card customers">
        <div class="stat-number"><?php echo e($activeCarts); ?></div>
        <div class="stat-label">Active Carts</div>
        <a href="<?php echo e(route('admin.cart.index')); ?>" class="stat-link">More info →</a>
    </div>
    
    <div class="stat-card orders">
        <div class="stat-number"><?php echo e($totalCartItems); ?></div>
        <div class="stat-label">Total Cart Items</div>
        <a href="<?php echo e(route('admin.cart.index')); ?>" class="stat-link">More info →</a>
    </div>
    
    <div class="stat-card pending">
        <div class="stat-number">$<?php echo e(number_format($totalCartValue, 2)); ?></div>
        <div class="stat-label">Total Cart Value</div>
        <a href="<?php echo e(route('admin.cart.index')); ?>" class="stat-link">More info →</a>
    </div>
</div>

<!-- Earnings Overview Chart -->
<div class="chart-section">
    <div class="chart-header">
        <h3>Earnings Overview</h3>
    </div>
    <canvas id="earningsChart"></canvas>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
// Initialize earnings chart
const ctx = document.getElementById('earningsChart').getContext('2d');
const earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($earningsData['labels']); ?>,
        datasets: [{
            label: 'Earnings ($)',
            data: <?php echo json_encode($earningsData['data']); ?>,
            borderColor: '#4a90e2',
            backgroundColor: 'rgba(74, 144, 226, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f0f0f0'
                }
            },
            x: {
                grid: {
                    color: '#f0f0f0'
                }
            }
        }
    }
});
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.shared', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\thenn\OneDrive\Desktop\softora\08 25\admin-panel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>