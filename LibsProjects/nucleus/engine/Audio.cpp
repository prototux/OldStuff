#include "Audio.hpp"
#include <iostream>
using namespace ne;

Audio::Audio()
{
	Sound *first = new Sound;
	this->SoundsList = first;
	this->SoundsCount = 0;
}

void Audio::LoadSound(std::string  filename, std::string name)
{
	Sound *NewSound;
	if (this->SoundsCount)
		NewSound = new Sound;
	else
		NewSound = this->SoundsList;

	if (this->SoundsList->Buffer.LoadFromFile(filename))
	{
		this->SoundsList->Sound.SetBuffer(this->SoundsList->Buffer);
		this->SoundsList->Sound.SetPitch(1.0f);
		this->SoundsList->Sound.SetVolume(20.f);
	}

	if (this->SoundsCount)
	{
		Sound *TmpSound = this->SoundsList;
		while (TmpSound->next)
			TmpSound = TmpSound->next;
		TmpSound->next = NewSound;
		NewSound->next = 0;
	}
	else
	{
		NewSound->next = 0;
		this->SoundsCount++;
	}
}

void Audio::PlaySound(std::string  name)
{
	Sound *CurrentSound = this->SoundsList;
	while (CurrentSound->next && CurrentSound->name != name)
		CurrentSound = CurrentSound->next;
	CurrentSound->Sound.Play();
}

void Audio::PlayMusic(std::string  filename)
{
    this->Music.OpenFromFile(filename);
    this->Music.Play();
}