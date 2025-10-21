<?php

function current_branch_id(): ?int {
    return session('branch_id'); // আপনার existing branch switch যেটা সেট করে
}