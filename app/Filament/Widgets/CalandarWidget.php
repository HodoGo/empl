<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;
use Filament\Forms;
use Saade\FilamentFullCalendar\Actions\EditAction;
use App\Enums\EventType;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Actions\Action;

class CalandarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Event::class;

    public function fetchEvents(array $fetchInfo): array
    {
                return Event::where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Event $event) {

                return [
                    'id'    => $event->id,
                    'title' => $event->employee->fullName,
                    'start' => $event->start,
                    'end'   => $event->end,
                    'color' => $event->type_event->getCal(),
                ];
            })
            ->toArray();
    }

    public static function canView(): bool
    {
        return false;
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name'),
            Forms\Components\ToggleButtons::make('type_event')
                            ->inline()
                            ->options(EventType::class)
                            ->required(),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start'),

                    Forms\Components\DateTimePicker::make('end'),
                ]),
            Forms\Components\Select::make('employee_id')
                ->relationship(name: 'employee', titleAttribute: 'lastname')
                ->preload()
                ->searchable(['lastname', 'firstname'])
                ->createOptionForm([
                    Forms\Components\TextInput::make('lastname')
                        ->required(),
                    Forms\Components\TextInput::make('firstname')
                        ->required(),
                ]),
        ];
    }
    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->mountUsing(
                    function (Event $record, Forms\Form $form, array $arguments) {
                        $form->fill([
                            'name' => $record->name,
                            'start' => $arguments['event']['start'] ?? $record->start,
                            'end' => $arguments['event']['start'] ?? $record->end,
                            'type_event' => $record->type_event,
                        ]);
                    }
                ),
            Actions\DeleteAction::make(),
        ];
    }
    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $form->fill([
                            'start' => $arguments['start'] ?? null,
                            'end' => $arguments['end'] ?? null
                        ]);
                    }
                ),
           Action::make('Generate events')
                ->action(function (){
                    // получить все записи из таблицы сотрудников
                    $employees = Employee::all();
                    $lastEvent = Event::where('type_event', 'duty')->latest('start')->first();
                    if (Event::count()>0) {
                        $currentDate = Carbon::parse($lastEvent->start)->addDay();
                    }
                    else{
                        $currentDate = Carbon::now();
                    }

                    // Циклом создаем и сохраняем события
                    foreach ($employees as $employee) {
                        $model = new Event();
                        $model->start = $currentDate;
                        $model->type_event = 'Duty';
                        $model->end = $currentDate;
                        $model->employee_id = $employee->id; // Используйте соответствующий идентификатор сотрудника
                        $model->name = "{$employee->lastname} {$employee->firstname}";
                        $model->save();

                        // Увеличиваем текущую дату на один день
                        $currentDate->addDay();
                    }
                }),

        ];
    }

}
