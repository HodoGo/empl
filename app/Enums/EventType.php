<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EventType: string implements HasColor, HasIcon, HasLabel
{
    case Vacation = 'Vacation';

    case SickLeave = 'SickLeave';

    case Duty = 'Duty';

    case PersonalExpense = 'PersonalExpense';

    public function getLabel(): string
    {
        return match ($this) {
            self::Vacation => 'Vacation',
            self::SickLeave => 'Sick Leave',
            self::Duty => 'Duty',
            self::PersonalExpense => 'Personal Expense',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Vacation => 'info',
            self::SickLeave => 'warning',
            self::Duty => 'success',
            self::PersonalExpense => 'danger',
        };
    }
    public function getCal(): string | array | null
    {
        return match ($this) {
            self::Vacation => 'blue',
            self::SickLeave => 'orange',
            self::Duty => 'green',
            self::PersonalExpense => 'red',
        };
    }
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Vacation => 'heroicon-m-sun',
            self::SickLeave => 'heroicon-m-beaker',
            self::Duty => 'heroicon-m-clock',
            self::PersonalExpense => 'heroicon-m-currency-dollar',
        };
    }
}
