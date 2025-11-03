#!/bin/bash

# Check Queue Worker Status

echo "=========================================="
echo "Queue Worker Status"
echo "=========================================="
echo ""

# Check if running
if pgrep -f "artisan queue:work" > /dev/null; then
    PID=$(pgrep -f "artisan queue:work")
    echo "✅ Queue worker is RUNNING"
    echo "   PID: $PID"
    echo ""
    echo "Process details:"
    ps aux | grep "artisan queue:work" | grep -v grep
else
    echo "❌ Queue worker is NOT running"
    echo ""
    echo "To start queue worker, run:"
    echo "  ./start-queue-worker.sh"
fi

echo ""
echo "=========================================="
echo "Queue Statistics"
echo "=========================================="
echo ""

# Check pending jobs (if database accessible)
if [ -f .env ]; then
    echo "Pending jobs in queue:"
    php artisan queue:monitor 2>/dev/null || echo "  (Run 'php artisan queue:monitor' for details)"
fi

echo ""
echo "Recent log entries:"
if [ -f storage/logs/worker.log ]; then
    tail -n 10 storage/logs/worker.log
else
    echo "  No log file found"
fi

