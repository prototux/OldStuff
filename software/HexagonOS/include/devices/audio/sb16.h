#ifndef _SB16_H_
#define _SB16_H_

#define	PEEK_DLY		100
#define	POKE_DLY		100
#define	RESET_DLY		100

#define	SBREG_MIX_ADR	0x04
#define	SBREG_MIX_DATA	0x05
#define	SBREG_RESET		0x06	// write only
#define	SBREG_READ		0x0A	//read only
#define	SBREG_WRITE		0x0C	// read and write
#define	SBREG_POLL		0x0E	// read only



#define	SBCMD_PCM8_MONO_SINGLE_OUTPUT	0x14	/* 8-bit DMA low-speed playback */
#define  SBCMD_PCM8_MONO_SINGLE_INPUT		0x24		
#define  SBCMD_PCM8_MONO_AUTO_INPUT		0x2C
#define  SBCMD_PCM8_MONO_AUTO_OUTPUT		0x1C

#define  SBCMD_PCM8_SINGLE_INPUT				0xC8
#define  SBCMD_PCM8_SINGLE_OUTPUT			0xC0
#define  SBCMD_PCM16_SINGLE_INPUT				0xB8
#define  SBCMD_PCM16_SINGLE_OUTPUT			0xB0

#define  SBCMD_PCM8OR16_TSR_INPUT			0x42
#define  SBCMD_PCM8OR16_TSR_OUTPUT			0x41

#define  SB_PCM8_MONO							0x00
#define  SB_PCM8_STEREO							0x20
#define  SB_PCM16_MONO							0x10
#define  SB_PCM16_STEREO						0x30

#define  SB_MODE_PCM8_SINGLE_INPUT			0xC8
#define  SB_MODE_PCM8_SINGLE_OUTPUT			0xC0
#define  SB_MODE_PCM16_SINGLE_INPUT			0xB8
#define  SB_MODE_PCM16_SINGLE_OUTPUT		0xB0

#define  SBCMD_PCM8_AUTO_INPUT				0xCE
#define  SBCMD_PCM8_AUTO_OUTPUT				0xC6
#define  SBCMD_PCM16_AUTO_INPUT				0xBE
#define  SBCMD_PCM16_AUTO_OUTPUT			0xB6

#define	SBCMD_SET_RATE			0x40	/* set sampling rate */
#define	SBCMD_SPKR_ON			0xD1	/* turn on speaker */
#define	SBCMD_SPKR_OFF			0xD3	/* turn OFF speaker */
#define	SBCMD_VERSION			0xE1	/* get DSP version */
#define	SBCMD_INTERRUPT		0xF2	/* generate an interrupts (for IRQ probe) */

#define SBREG_MIX_IRQSET		0x80
#define SBREG_MIX_DMASET		0x81
#define SBREG_MIX_ISRPOLL		0x82

#define SB_IRQ2	1
#define SB_IRQ5	(1 << 1)
#define SB_IRQ7	(1 << 2)
#define SB_IRQ10	(1 << 3)

#define SB_DMA0	1
#define SB_DMA1	1 << 1
#define SB_DMA3	1 << 3
#define SB_DMA5	1 << 5
#define SB_DMA6	1 << 6
#define SB_DMA7	1 << 7

/*
Digtized Sound I/O Programming
______________________________

You can set the transfer rate as follows

Time constant = 65536 - (256000000 / (channels * sampling rate)
channelmay be 1 for mono or 2 for stereo

Direct mode:
___________
only mono 8 bit unsigned PCM is supported. CPU refreshes in the timer

Single Cycle DMA mode
____________________
8bit unsigned and 16 bit signed PCM is supported.
DSP is allowed to make one transfer only. DSP generates interrupt at end of transfer.

Auto Initialize DMA mode
_____________________
DMA automatically reload transfer addr and count. DSP will generate interrupt at constant intervals.
double buffering is used.

In Double buffering you begin at the middle of buffer and start DMA transfer 
once block is transfered interrupt occurs and you can replace that block with new data

High Speed  DMA mode ( both single cycle and Auto initialize mode are used)
___________________
dsp only accepts data transfer .

supposrts both mono and stereo 8bit and 16bit unsigned PCM data

ADPCM data mode
________________
stores relative data

General procedure for DMA mode transfer
___________________________________

1. Set up ISR
2. program DMA controller
3. program DSP sampling rate
4. program DSP with DMA transfer mode and length to start I/O
5. Service DSP interrupts
6. restore original service routine

Handling DSP Sound I/O Interrupt
___________________________

DSP generates interrupt at end of each block

1. Program DSP for next block
2. Acknowledge

1. turn on DAC speaker (2xC) for digitized O/p  value = 0xD3
2. Program DMA controller
3. set DSP transfer time constant  
	2xC  = 0x40
	2xC = TimeConstant
4. send io command followed by  data transfer count
	2xC = command
	2xC = length.lowbyte
	2xC = length.highbyte

	length must be one less than number of bytes to be transfered

	blocksize is one less than number of samples to be transfered
	
	to stop auto initialize mode
	1.2xC = 0xDA for 8bit transfer
	2.2xC = 0xD9 for 16bit transfer
	3.Program DSP for single cycle transfer mode	


Mixer Chip Programming
________________________

2x4 address port(write only)
2x5 data port(read/write)

1. write index of mixer register to address port
2. write/read mixer register value to/from data port.


*/

struct sbinfo
{
uint32_t IoBase;
uint32_t bufphys;
uint32_t buflength;
void * bufvirt;
void *current;
int done;
int shadow;
};

sbinfo SbInfo;
uint16_t ioaddr;

void DmaInit(unsigned channel, Uint32 address, size_t size, Boolean autoinit, Boolean read);


#endif