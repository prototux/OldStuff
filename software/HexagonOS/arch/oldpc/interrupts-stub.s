; Interrupts assembly code:
; Basically, each interrupt push it's interrupt number, then, jumps to the ir_common_stub
; This is needed as if we don't do that, ALL interrupt handlers for ALL drivers are needed in the kernel
; So, doing this permit each driver to define it's own handler in it's own code.

section .text

extern k_int_exec_handlers

%macro MAKE_IRQ 1
	global _asm_irq_%1
	_asm_irq_%1:
		%if (%1 != 8 && %1 != 17 && %1 != 30) && (%1 < 10 || %1 > 14)
			push dword 0 ; Dummy error code needs to be pushed on some interrupts
		%endif
		push dword %1
		jmp _irq_common_stub
%endmacro


; Create the 48 interrupt-routines
%assign irq_number 0
%rep 49
	MAKE_IRQ irq_number
	%assign irq_number irq_number+1
%endrep


; Called from each interrupt routine, saves registers and jumps to C-code
_irq_common_stub:
	push eax
	push ecx
	push edx
	push ebx
	push ebp
	push esi
	push edi
	push ds
	push es
	push fs
	push gs

	mov ax, 0x10
	mov ds, ax
	mov es, ax
	mov fs, ax
	mov gs, ax

	push esp ; parameter of _irq_handler
	call k_int_exec_handlers
	mov esp, eax ; return value: changed or unchanged esp

	pop gs
	pop fs
	pop es
	pop ds
	pop edi
	pop esi
	pop ebp
	pop ebx
	pop edx
	pop ecx
	pop eax

	add esp, 8
	iret